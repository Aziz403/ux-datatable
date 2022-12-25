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

        if($withSelect){
            //add query select from columns
            if($query['columns']){
                $select = "";
                foreach ($query['columns'] as $column){
                    $indexOfColumn = $column['data'];
                    $columnInfo = $this->datatable->getColumnByIndex($indexOfColumn);
                    if(!$columnInfo){
                        throw new \Exception("Column Has Index ".$indexOfColumn." Not Found in EntityDatatable::columns");
                    }

                    if($columnInfo instanceof  EntityColumn){

                    }
                    elseif($columnInfo instanceof TwigColumn){

                    }
                    else{
                        $select .= "$this->alias.$columnInfo,";
                    }
                }
                $select = substr_replace($select ,"", -1);
                $q->select($select);
            }
        }

        if($withOrder){
            //add order in query base on columns
            foreach ($query['orders'] as $order){
                $indexOfColumn = $order['column'];
                $dir = $order['dir'];
                $columnInfo = $this->datatable->getColumnByIndex($indexOfColumn);
                if($columnInfo instanceof EntityColumn){

                }
                elseif($columnInfo instanceof TwigColumn){

                }
                else{
                    $q->addOrderBy("$this->alias.$columnInfo",$dir);
                }
            }
        }

        //add join entities
        //foreach columns -> if col instanseof EntityColumn

        //add search query
        if($query['columns']){
            foreach ($query['columns'] as $column){
                if($searchValue = $column['search']['value']){
                    $indexOfColumn = $column['data'];
                    $columnInfo = $this->datatable->getColumn($indexOfColumn);

                }
            }
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