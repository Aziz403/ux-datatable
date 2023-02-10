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

use Aziz403\UX\Datatable\Helper\DatatableQueriesHelper;
use Aziz403\UX\Datatable\Service\DataService;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

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

    protected ?string $path;

    private ?Criteria $criteria = null;

    private ?Request $request = null;

    private DatatableQueriesHelper $queriesService;

    public function __construct(
        string $className,
        EntityRepository $repository,
        Environment $environment,
        TranslatorInterface $translator,
        array $config
    )
    {
        parent::__construct($environment,$translator,$config);
        
        $this->className = $className;
        $this->attributes['id'] = "datatable_".DataService::toSnakeCase($className);
        $this->options = array_merge(self::DEFAULT_DATATABLE_OPTIONS,$this->options);

        $this->queriesService = new DatatableQueriesHelper($repository,$this);
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

    /**
     * @return string
     */
    public function getLanguage(): string
    {
        if($this->language==null || $this->language=='request'){
            $this->language = $this->request->getLocale();
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
            throw new \Exception(Request::class." Not Found,Maybe you forget send Request in EntityDatatable::handleRequest method");
        }

        $query = [
            'columns' => $this->request->get('columns'),
            'orders' => $this->request->get('order'),
            'search' => $this->request->get('search'),
            'start' => $this->request->get('start'),
            'length' => $this->request->get('length')
        ];
        //create query
        $records = $this->queriesService->findRecords($query);
        $recordsTotal = $this->queriesService->countRecords();
        $recordsFiltered = $this->queriesService->countRecords($query);

        //trait data by environment
        $data = $this->templatingService->renderData($records);

        //clear request
        $draw = $this->request->get('draw',1);
        $this->request = null;

        //save data to later usage
        return new JsonResponse([
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data,
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