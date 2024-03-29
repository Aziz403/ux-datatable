<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Aziz Benmallouk <azizbenmallouk4@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Aziz403\UX\Datatable\Model;

use Aziz403\UX\Datatable\Event\Events;
use Aziz403\UX\Datatable\Event\RenderDataEvent;
use Aziz403\UX\Datatable\Event\RenderQueryEvent;
use Aziz403\UX\Datatable\Service\DataService;
use Doctrine\Common\Collections\Criteria;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author Aziz Benmallouk <azizbenmallouk4@gmail.com>
 */
class EntityDatatable extends AbstractDatatable
{
    const DEFAULT_DATATABLE_OPTIONS = [
        'processing' => true,
        'serverSide' => true
    ];

    protected string $className = "";

    protected ?string $path = "";

    private ?Criteria $criteria = null;

    private ?Request $request = null;

    public function __construct(
        string $className,
        EventDispatcherInterface $dispatcher,
        TranslatorInterface $translator,
        array $config,
        string $locale
    )
    {
        parent::__construct($dispatcher,$translator,$config,$locale);
        
        $this->className = $className;
        $this->attributes['id'] = "datatable_".DataService::toSnakeCase($className);
        $this->options = array_merge(self::DEFAULT_DATATABLE_OPTIONS,$this->options);
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * @param string $className
     * @return EntityDatatable
     */
    public function setClassName(string $className): EntityDatatable
    {
        $this->className = $className;
        
        return $this;
    }

    /**
     * @param string|null $path
     */
    public function setPath(?string $path): void
    {
        $this->path = $path;
    }

    /**
     * @return Criteria|null
     */
    public function getCriteria(): ?Criteria
    {
        return $this->criteria;
    }

    /**
     * @param Criteria $criteria
     */
    public function setCriteria(Criteria $criteria): void
    {
        $this->criteria = $criteria;
    }

    public function addFilter(callable $function,int $priority = 0)
    {
        $this->dispatcher->addListener(Events::SEARCH_QUERY,$function,$priority);
    }

    /**
     * @return string
     */
    public function getLanguage(): string
    {
        if($this->language==null || $this->language=='request'){
            if($this->request){
                $this->language = $this->request->getLocale();
            }
            else{
                $this->language = $this->locale;
            }
        }
        return $this->language;
    }

    /**
     * @return bool
     */
    public function isSubmitted():bool
    {
        if(!$this->request){
            return false;
        }
        return $this->request->get('draw',false);
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function handleRequest(Request $request): self
    {
        $this->request = $request;
        $this->path = $request->getPathInfo();
        $this->options['ajax'] = $this->path;

        return $this;
    }

    /**
     * @return JsonResponse
     */
    public function getResponse(): JsonResponse
    {
        //check if request handled
        if(!$this->request){
            throw new \Exception(sprintf("%s Not Found, Maybe you forget send Request in EntityDatatable::handleRequest","Request"));
        }

        $query = [
            'columns' => $this->request->get('columns'),
            'orders' => $this->request->get('order'),
            'search' => $this->request->get('search'),
            'start' => $this->request->get('start'),
            'length' => $this->request->get('length')
        ];

        $event = new RenderQueryEvent($this,$query);
        $this->dispatcher->dispatch($event,Events::PRE_QUERY);

        $recordsTotal = $event->getRecordsTotal();
        $recordsFiltered = $event->getRecordsFiltered();
        $records = $event->getRecords();

        $event = new RenderDataEvent($this,$records);
        $this->dispatcher->dispatch($event,Events::PRE_DATA);
        
        $records = $event->getRecords();

        //clear request
        $draw = $this->request->get('draw',1);
        $this->request = null;

        //save data to later usage
        return new JsonResponse([
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $records,
            "draw" => $draw
        ]);
    }

    /**
     * @return array
     */
    public function createView(): array
    {
        return [
            "path" => $this->path,
            "options" => array_merge(
                array_merge(
                    $this->options,
                    ["columnDefs" => $this->getColumnDefs()]
                ),
                ["language" => $this->getLanguageData()]
            ),
        ];
    }
}