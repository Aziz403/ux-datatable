<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Aziz Benmallouk <azizbenmallouk4@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Aziz403\UX\Datatable\Helper;

use Aziz403\UX\Datatable\Column\EntityColumn;
use Aziz403\UX\Datatable\Column\InlineTwigColumn;
use Aziz403\UX\Datatable\Column\TwigColumn;
use Aziz403\UX\Datatable\Model\EntityDatatable;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * @author Aziz Benmallouk <azizbenmallouk4@gmail.com>
 */
class DatatableQueriesHelper
{
    private EntityRepository $repository;
    private EntityDatatable $datatable;
    private string $alias;

    public function __construct(EntityRepository $repository,EntityDatatable $datatable)
    {
        $this->repository = $repository;
        $this->datatable = $datatable;
        $this->alias = "entity".random_int(9,999);
    }

    public function getQuery(array $query,bool $withSelect = true,bool $withOrder = true,bool $withPagination = true): QueryBuilder
    {
        //create query
        $q = $this->repository
            ->createQueryBuilder($this->alias);

        //add join entities
        /** @var EntityColumn $column */
        foreach ($this->datatable->getColumnsByType(EntityColumn::class) as $column){
            $q = $column->join($q);
        }

        if($withOrder){
            //add order in query base on columns
            foreach ($query['orders'] as $order){
                $indexOfColumn = $order['column'];
                $dir = $order['dir'];
                $columnInfo = $this->datatable->getColumnByIndex($indexOfColumn);
                if($columnInfo instanceof EntityColumn){
                    $q->addOrderBy($columnInfo->getEntity().".".$columnInfo->getField(),$dir);
                }
                else{
                    $q->addOrderBy("$this->alias.$columnInfo",$dir);
                }
            }
        }

        //add global search query
        if($value = $query['search']['value']){
            foreach ($this->datatable->getSearchableColumns() as $column){
                $q = $column->search($q,$value);
            }
        }

        //add filter columns query
        if($query['columns']){
            foreach ($query['columns'] as $column){
                if($searchValue = $column['search']['value']){
                    $indexOfColumn = $column['data'];
                    $columnInfo = $this->datatable->getColumn($indexOfColumn);

                }
            }
        }

        //add criteria if exists
        if($criteria = $this->datatable->getCriteria()){
            $q->addCriteria($criteria);
        }

        if($withPagination){
            //add pagination to query
            $q->setFirstResult($query['start'])
                ->setMaxResults($query['length']);
        }

        return $q;
    }

    public function findRecords(array $query)
    {
        return $this->getQuery($query)
            ->getQuery()
            ->getResult();
    }

    public function countRecords(array $query = null)
    {
        $q = $this->repository->createQueryBuilder($this->alias);
        if($query){
            $q = $this->getQuery($query,false,false,false);
        }
        return $q->select("COUNT($this->alias.id)")
            ->getQuery()
            ->getSingleScalarResult();
    }
}