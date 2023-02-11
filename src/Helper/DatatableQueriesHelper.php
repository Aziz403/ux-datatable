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
use Aziz403\UX\Datatable\Event\Events;
use Aziz403\UX\Datatable\Event\RenderSearchQueryEvent;
use Aziz403\UX\Datatable\Model\EntityDatatable;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @author Aziz Benmallouk <azizbenmallouk4@gmail.com>
 */
class DatatableQueriesHelper
{
    private EntityDatatable $datatable;
    private EntityRepository $repository;
    private EventDispatcherInterface $dispatcher;
    private string $alias;

    public function __construct(EntityManagerInterface $manager,EventDispatcherInterface $dispatcher,EntityDatatable $datatable)
    {
        $this->datatable = $datatable;
        $this->repository = $manager->getRepository($datatable->getClassName());
        if(!$this->repository){
            throw new \LogicException(sprintf("%s Repository Not Found ",$datatable->getClassName()));
        }
        $this->dispatcher = $dispatcher;
        $this->alias = "entity";
    }

    public function getBaseQuery(): QueryBuilder
    {
        $q = $this->repository->createQueryBuilder($this->alias);

        //add criteria if exists
        if($criteria = $this->datatable->getCriteria()){
            $q->addCriteria($criteria);
        }
        return $q;
    }

    public function getFilteredQuery(array $query,bool $withOrder = true,bool $withPagination = true): QueryBuilder
    {
        //create query
        $q = $this->getBaseQuery();

        $event = new RenderSearchQueryEvent($this->datatable,$q);
        $this->dispatcher->dispatch($event,Events::SEARCH_QUERY);
        $q = $event->getQuery();

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

        if($withPagination){
            //add pagination to query
            $q->setFirstResult($query['start'])
                ->setMaxResults($query['length']);
        }

        return $q;
    }

    public function findRecords(array $query)
    {
        return $this->getFilteredQuery($query)
            ->getQuery()
            ->getResult();
    }

    public function countRecords(array $query = null)
    {
        if($query){
            $q = $this->getFilteredQuery($query,false,false);
        }
        else{
            $q = $this->getBaseQuery();
        }

        return $q->select("COUNT($this->alias.id)")
            ->getQuery()
            ->getSingleScalarResult();
    }
}