<?php

namespace Aziz403\UX\Datatable\Helper;

use Aziz403\UX\Datatable\Model\EntityDatatable;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class DatatableQueriesHelper
{
    private EntityRepository $repository;
    private EntityDatatable $datatable;

    public function __construct(EntityRepository $repository,EntityDatatable $datatable)
    {
        $this->repository = $repository;
        $this->datatable = $datatable;
    }

    public function getDataQuery(array $query): QueryBuilder
    {
        return $this
            ->repository
            ->createQueryBuilder('e');
    }

    public function findRecords(array $query)
    {
        return $this
            ->getDataQuery($query)
            ->getQuery()
            ->getResult();
    }

    public function countRecords(array $query = null)
    {
        $q = $this->repository->createQueryBuilder('e');
        if($query){
            $q = $this->getDataQuery($query);
        }
        return $q
            ->select('COUNT(*)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}