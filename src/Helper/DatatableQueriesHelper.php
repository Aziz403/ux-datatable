<?php

namespace Aziz403\UX\Datatable\Helper;

use Aziz403\UX\Datatable\Column\EntityColumn;
use Aziz403\UX\Datatable\Column\TwigColumn;
use Aziz403\UX\Datatable\Model\EntityDatatable;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

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
                    $columnInfo = $this->datatable->getColumn($column['data']);
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
                $data = $order['column'];
                $dir = $order['dir'];
                $columnInfo = $this->datatable->getColumn($data);
                if($columnInfo instanceof EntityColumn){

                }
                elseif($columnInfo instanceof TwigColumn){

                }
                else{
                    $q->addOrderBy($data,$dir);
                }
            }
        }

        //add join entities
        //foreach columns -> if col instanseof EntityColumn

        //add search query
        if($query['columns']){
            foreach ($query['columns'] as $column){
                if($searchValue = $column['search']['value']){
                    $columnInfo = $this->datatable->getColumn($column['data']);

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