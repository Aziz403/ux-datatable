<?php

namespace Aziz403\UX\Datatable\Model;

use Aziz403\UX\Datatable\Column\AbstractColumn;
use Aziz403\UX\Datatable\Repository\DatatableRepository;
use Aziz403\UX\Datatable\Helper\DatatableQueriesHelper;
use Aziz403\UX\Datatable\Helper\DatatableTemplatingHelper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class EntityDatatable extends AbstractDatatable
{
    private string $className;
    private string $path;
    private array $columns;

    private Request $request;

    private DatatableQueriesHelper $queriesService;
    private DatatableTemplatingHelper $templatingService;

    public function __construct(string $className,EntityRepository $repository,Environment $environment)
    {
        $this->className = $className;
        $this->columns = [];
        $this->queriesService = new DatatableQueriesHelper($repository,$this);
        $this->templatingService = new DatatableTemplatingHelper($environment,$this);
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

    public function isSubmitted():bool
    {
        $draw = $this->request->get('draw');
        $orders = $this->request->get('orders');
        $columns = $this->request->get('columns');
        $start = $this->request->get('start');
        $length = $this->request->get('length');
        return $draw && $orders && $columns & $start && $length;
    }

    public function createView(): array
    {
        return [
            "path" => $this->path,
            "options" => $this->options,
        ];
    }

    public function handleRequest(Request $request): self
    {
        $this->request = $request;
        $this->path = $request->getPathInfo();

        return $this;
    }

    /**
     * @return JsonResponse
     */
    public function getResponse(): JsonResponse
    {
        //check if request handled
        if($this->request){
            throw new \Exception(Request::class." Not Found,Maybe you forget send Request in EntityDatatable::handleRequest method");
        }

        $query = [
            'columns' => $this->request->get('columns'),
            'orders' => $this->request->get('orders'),
            'start' => $this->request->get('start'),
            'length' => $this->request->get('length')
        ];
        //create query
        $records = $this->queriesService->findRecords($query);
        $recordsTotal = $this->queriesService->countRecords();
        $recordsFiltered = $this->queriesService->countRecords($query);

        //trait data by environment
        $data = $this->templatingService->renderData($records);

        //save data to later usage
        return new JsonResponse([
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data,
            "draw" => $this->request->get('draw',1)
        ]);
    }
}