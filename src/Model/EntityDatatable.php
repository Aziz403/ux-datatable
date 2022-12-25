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

use Aziz403\UX\Datatable\Column\AbstractColumn;
use Aziz403\UX\Datatable\Column\TwigColumn;
use Aziz403\UX\Datatable\Helper\DatatableQueriesHelper;
use Aziz403\UX\Datatable\Helper\DatatableTemplatingHelper;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;

/**
 * @author Aziz Benmallouk <azizbenmallouk4@gmail.com>
 */
class EntityDatatable extends AbstractDatatable
{
    const DEFAULT_DATATABLE_OPTIONS = [
        'dom' => "<Bl>t<ip>r",
        'processing' => true,
        'serverSide' => true,
        'order' => [[0, 'desc']]
    ];

    private string $className;
    private ?string $path;
    private array $columns;

    private ?Request $request;

    private DatatableQueriesHelper $queriesService;
    private DatatableTemplatingHelper $templatingService;

    public function __construct(string $className,EntityRepository $repository,Environment $environment)
    {
        $this->className = $className;
        $this->columns = [];
        $this->queriesService = new DatatableQueriesHelper($repository,$this);
        $this->templatingService = new DatatableTemplatingHelper($environment,$this);
        $this->options = self::DEFAULT_DATATABLE_OPTIONS;
        $this->attributes['id'] = "table".random_int(9,999);
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * @return AbstractColumn[]
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * @return TwigColumn[]
     */
    public function getTwigColumns(): array
    {
        return array_filter($this->columns,function (AbstractColumn $column) {
            return $column instanceof TwigColumn;
        });
    }

    /**
     * @param string $data
     * @return AbstractColumn|null
     */
    public function getColumn(string $data): ?AbstractColumn
    {
        $res = array_filter($this->columns,function (AbstractColumn $column) use($data){
            return $column->getData()===$data;
        });
        if(count($res)==0){
            return null;
        }
        return reset($res);
    }

    /**
     * @param int $index
     * @return AbstractColumn|null
     */
    public function getColumnByIndex(int $index): ?AbstractColumn
    {
        return $this->columns[$index];
    }

    /**
     * @param string $data
     * @return AbstractColumn|null
     */
    public function getColumnIndex(string $data): ?int
    {
        return array_search(
            $this->getColumn($data),
            $this->columns
        );
    }

    /**
     * @param AbstractColumn[] $columns
     * @return EntityDatatable
     */
    public function setColumns(array $columns): self
    {
        $this->columns = $columns;

        return $this;
    }

    /**
     * @param AbstractColumn[] $columns
     * @return EntityDatatable
     */
    public function addColumns(array $columns): self
    {
        $this->columns = array_merge($this->columns,$columns);

        return $this;
    }

    /**
     * @param AbstractColumn $column
     * @return EntityDatatable
     */
    public function addColumn(AbstractColumn $column): self
    {
        $this->columns[] = $column;

        return $this;
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
     * @return array
     */
    public function createView(): array
    {
        return [
            "path" => $this->path ?? 'null',
            "options" => $this->options,
        ];
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
}