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

    public function getQuery(array $query,bool $withOrder = true,bool $withPagination = true): QueryBuilder
    {
        //create query
        $q = $this->repository
            ->createQueryBuilder($this->alias);

        //add join entities
        /** @var EntityColumn $column */
        foreach ($this->datatable->getColumnsByType(EntityColumn::class) as $column){
            $column->join($q);
        }

        if($withOrder){
            //add order in query base on columns
            foreach ($query['orders'] as $order){
                $indexOfColumn = $order['column'];
                $dir = $order['dir'];
                $column = $this->datatable->getColumnByIndex($indexOfColumn);

                $column->order($q,$dir);
            }
        }

        //add global search query
        if($value = $query['search']['value']){
            $search =  [];
            foreach ($this->datatable->getSearchableColumns() as $column){
                 $search[] = $column->search($q,$value);
            }
            $q->andWhere($q->expr()->orX(...$search));
        }

        //add filter columns query
        if($query['columns']){
            foreach ($query['columns'] as $queryColumn){
                if($value = $queryColumn['search']['value']){
                    $indexOfColumn = $queryColumn['data'];
                    $column = $this->datatable->getColumn($indexOfColumn);

                    $q->andWhere($column->search($q,$value));
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
            $q = $this->getQuery($query,false,false);
        }
        return $q->select("COUNT($this->alias.id)")
            ->getQuery()
            ->getSingleScalarResult();
    }
}