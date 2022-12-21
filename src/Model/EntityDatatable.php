<?php

namespace Aziz403\UX\Datatable\Model;

use Aziz403\UX\Datatable\Columns\AbstractColumn;
use Aziz403\UX\Datatable\Columns\TwigColumn;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Symfony\Component\HttpFoundation\Request;

class EntityDatatable extends AbstractDatatable
{
    private string $entityName;
    private string $path;
    private array $columns;
    private ?Criteria $criteria;

    public function __construct(string $entityName,string $path)
    {
        $this->entityName = $entityName;
        $this->path = $path;
        $this->columns = [];
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return AbstractColumn[]
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * @param AbstractColumn[] $columns
     */
    public function setColumns(array $columns): self
    {
        $this->columns = $columns;

        return $this;
    }

    /**
     * @return Criteria|null
     */
    public function getCriteria(): ?Criteria
    {
        return $this->criteria;
    }

    /**
     * @param Criteria|null $criteria
     */
    public function setCriteria(?Criteria $criteria): self
    {
        $this->criteria = $criteria;

        return $this;
    }

    /**
     * @return string
     */
    public function getEntityName(): string
    {
        return $this->entityName;
    }

    public function createView(): array
    {
        return [
            'path' => $this->path,
            'columns' => $this->buildView(),
            'options' => $this->options
        ];
    }

    private function buildCriteria(Request $request) :Criteria
    {
        $criteria = new Criteria();



        return $criteria;
    }

    public function buildView()
    {
        return array_map(function (AbstractColumn $column){
            return $column->build();
        },$this->columns);
    }

    public function buildData(ServiceEntityRepository $repository,Request $request)
    {
        $q = $repository->createQueryBuilder($this->entityName);

        if($this->criteria){
            $q->addCriteria($this->criteria);
        }

        $data = $q->addCriteria($this->buildCriteria($request));

        return [
            "recordsTotal" => $countAll,
            "recordsFiltered" => $countWithFilter,
            "draw" => $request->get('draw',1),
            "data" => $data,
        ];
    }
}