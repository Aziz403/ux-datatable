<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Aziz Benmallouk <azizbenmallouk4@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\UX\Datatable\Repository;


use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\UX\Datatable\Entity\DataTableInterface;

/**
 * @author Aziz Benmallouk <azizbenmallouk4@gmail.com>
 *
 * @final
 */
class DatatableRepository extends BaseRepository
{
    const DATATABLE_SIMPLE_RESULT = 'DATATABLE_SIMPLE_RESULT';
    const DATATABLE_SCALAR_RESULT = 'DATATABLE_SCALAR_RESULT';
    const DATATABLE_WITHOUT_SELECT = 'DATATABLE_WITHOUT_SELECT';

    public function __construct(ManagerRegistry $registry,EntityManagerInterface $entityManager,string $entityClass,string $alias)
    {
        parent::__construct($registry,$entityManager, $entityClass,$alias);
    }

    /* start - dataTable base request queries */
    public function getQueryWithRequest(string $result = self::DATATABLE_SIMPLE_RESULT, $columns = null,$start = null,$length = null,$orders = null)
    {
        $select = "";
        $groupBy = "";

        if($columns){
            $columns = array_map(function ($item){
                $item['column'] = call_user_func_array(array($this->entityClass, 'getDataTableColumn'), array($item['data']));
                return $item;
            },$columns);
            $columns = array_filter($columns,function ($item){
                return $item['data']!='action';
            });
        }

        $b = $this->getQuery();
        if($result==self::DATATABLE_SIMPLE_RESULT){
            $entities = [];
            // add select fields
            foreach ($columns as $col) {
                //nejbo data d column
                $column = $col['column'];
                //nexof wax entity ola column 3adi
                if(array_key_exists('entity',$column)){//ila kan entity
                    $field = $column['params']['displayName'];
                    $select .= $column['mapped'].'.'.$field.' AS '.$column['data'].',';
                    if(!in_array($column['mapped'],$entities)){
                        $entities[] = $column['mapped'];
                        if(!$this->hasQueryJoin($b,$column['mapped'])){
                            $b->join($this->alias.'.'.$column['mapped'],$column['mapped']);
                        }
                    }
                }
                else{//ila kan columns
                    $select .= $this->alias.'.'.$col['data'].',';
                }
            }
            // add order
            if($orders){
                foreach ($orders as $order){
                    $column = $columns[$order['column']]['column'];
                    //nexof wax entity ola column 3adi
                    if(array_key_exists('entity',$column)){
                        $b->orderBy($column['data'], $order['dir']);
                    }
                    else{
                        $b->orderBy($this->alias . '.' . $columns[$order['column']]['data'], $order['dir']);
                    }
                }
            }
        }
        elseif($result==self::DATATABLE_SCALAR_RESULT){
            $entities = [];
            // add select fields
            foreach ($columns as $col) {
                //nejbo data d column
                $column = $col['column'];
                //nexof wax entity ola column 3adi
                if(array_key_exists('entity',$column)){
                    if(!in_array($column['mapped'],$entities)) {
                        $entities[] = $column['mapped'];
                        if (!$this->hasQueryJoin($b, $column['mapped'])) {
                            $b->join($this->alias . '.' . $column['mapped'], $column['mapped']);
                        }
                    }
                }
            }
            $b->select("COUNT(".$this->alias.".id)");
        }

        //delete last ',' from query
        if(str_ends_with($select, ',')){ $select = substr_replace($select ,"", -1); }
        if($select){ $b->select($select); }
        if(str_ends_with($groupBy, ',')){ $groupBy = substr_replace($groupBy ,"", -1); }
        if($groupBy){ $b->groupBy($groupBy); }

        // add search in column if exists
        if($columns){
            foreach ($columns as $col){
                if($col['search']['value']){
                    $column = $col['column'];
                    $el = $col['data'];
                    $val = $col['search']['value'];
                    //nexof wax entity ola column 3adi
                    if(array_key_exists('entity',$column)){//ila kant entity
                        if($column['rowgroup']==DataTableInterface::SEARCH_FORMAT_API){//ila kan search f select element (o khetar item)
                            $b->andWhere($column['mapped'].'.id = :searchBy'.$el)
                                ->setParameter('searchBy'.$el,$val);
                        }
                        elseif($column['rowgroup']==DataTableInterface::SEARCH_FORMAT_ENTITY_FIELD){//ila kan search f input text element (ayedir search b dak field li searcha fih)
                            $field = $column['params']['displayName'];
                            $b->andWhere($column['mapped'].'.'.$field.' LIKE :searchBy'.$field)
                                ->setParameter('searchBy'.$field,'%'.$val.'%');
                        }
                    }
                    else{//ila kan column 3adi
                        // ila knan search b boolean
                        if($val=="false" || $val=="true"){
                            $b->andWhere($this->alias.'.'.$el.' = '.$val);
                        }
                        elseif ($column['rowgroup']==DataTableInterface::SEARCH_FORMAT_INT_BETWEEN || $column['rowgroup']==DataTableInterface::SEARCH_FORMAT_DATE_BETWEEN){
                            $vals = explode(' - ',$val);
                            if($vals[0]){
                                $b->andWhere($this->alias.'.'.$el.' >= :min'.$el)
                                    ->setParameter('min'.$el,$vals[0]);
                            }
                            if($vals[1]){
                                $b->andWhere($this->alias.'.'.$el.' <= :max'.$el)
                                    ->setParameter('max'.$el,$vals[1]);
                            }
                        }
                        //ila kna search 3adi text
                        else{
                            $b->andWhere($this->alias.'.'.$el.' LIKE :searchBy'.$el)
                                ->setParameter('searchBy'.$el,'%'.$val.'%');
                        }
                    }
                }
            }
        }

        // return query with offset & limit
        if($result==self::DATATABLE_SCALAR_RESULT){
            return $b;
        }
        elseif($result==self::DATATABLE_SIMPLE_RESULT){
            return $b->setFirstResult($start)
                ->setMaxResults($length);
        }
        else{
            return $b;
        }
    }
    /* end - dataTable base request queries */

    /* start - dataTable simple request queries */
    public function getDataWithRequest(Request $request)
    {
        return $this->getQueryWithRequest(
            self::DATATABLE_SIMPLE_RESULT,
            $request->get('columns',[]),
            $request->get('start',0),
            $request->get('length',25),
            $request->get('order')
        )
            ->getQuery()
            ->getArrayResult();
    }

    public function getCountWithRequest(Request $request)
    {
        $data = $this->getQueryWithRequest(
            self::DATATABLE_SCALAR_RESULT,
            $request->get('columns',[]),
            $request->get('start',0),
            $request->get('length',25)
        )
            ->getQuery()
            ->getSingleColumnResult();
        return reset($data);
    }
    /* end - dataTable simple request queries */

    /* start - dataTable simple request with condition queries */
    public function getDataWithRequestAndCondition(Request $request,string $propName,mixed $val,string $condition = "=")
    {
        return $this->getQueryWithRequest(
            self::DATATABLE_SIMPLE_RESULT,
            $request->get('columns',[]),
            $request->get('start',0),
            $request->get('length',25),
            $request->get('order')
        )
            ->andWhere($this->alias.'.'.$propName.' '.$condition.' :propVal')
            ->setParameter('propVal',$val)
            ->getQuery()
            ->getArrayResult();
    }

    public function getCountWithRequestAndCondition(Request $request,string $propName,mixed $val,string $condition = "=")
    {
        $data = $this->getQueryWithRequest(
            self::DATATABLE_SCALAR_RESULT,
            $request->get('columns',[]),
            $request->get('start',0),
            $request->get('length',25)
        )
            ->andWhere($this->alias.'.'.$propName.' '.$condition.' :propVal')
            ->setParameter('propVal',$val)
            ->getQuery()
            ->getSingleColumnResult();
        return reset($data);
    }
    /* end - dataTable simple request with condition queries */

    /* start - dataTable request queries by group */
    public function getDataWithRequestAndGroup(Request $request,string $groupEntityClass, int $id)
    {
        return $this->getQueryWithRequest(
            self::DATATABLE_SIMPLE_RESULT,
            $request->get('columns',[]),
            $request->get('start',0),
            $request->get('length',10),
            $request->get('order')
        )
            ->andWhere($groupEntityClass.'.id = :'.$groupEntityClass.'Id')
            ->setParameter($groupEntityClass.'Id',$id)
            ->getQuery()
            ->getArrayResult();
    }

    public function getCountWithRequestAndGroup(Request $request,string $groupEntityClass, int $id)
    {
        $data = $this->getQueryWithRequest(
            self::DATATABLE_SCALAR_RESULT,
            $request->get('columns',[]),
            $request->get('start',0),
            $request->get('length',10)
        )
            ->andWhere($groupEntityClass.'.id = :'.$groupEntityClass.'Id')
            ->setParameter($groupEntityClass.'Id',$id)
            ->getQuery()
            ->getSingleColumnResult();
        return reset($data);
    }

    public function getCountByGroup(string $groupEntityClass,int $id)
    {
        $b = $this->getQuery()
            ->select("COUNT(".$this->alias.".id)");

        if(!$this->hasQueryJoin($b,$groupEntityClass)){
            $b->join($this->alias.'.'.$groupEntityClass,$groupEntityClass);
        }

        $data = $b
            ->andWhere($groupEntityClass.'.id = :'.$groupEntityClass.'Id')
            ->setParameter($groupEntityClass.'Id',$id)
            ->getQuery()
            ->getSingleColumnResult();
        return reset($data);
    }
    /* end - dataTable request queries by group */

    /* start - dataTable request queries by multi group */
    public function getDataWithRequestAndGroups(Request $request,array $group)
    {
        $b = $this->getQueryWithRequest(
            self::DATATABLE_SIMPLE_RESULT,
            $request->get('columns',[]),
            $request->get('start',0),
            $request->get('length',10),
            $request->get('order')
        );
        foreach ($group as $propName => $id){
            $b->andWhere($propName.'.id = :'.$propName.'Id')
                ->setParameter($propName.'Id',$id);
        }
        return $b->getQuery()->getArrayResult();
    }

    public function getCountWithRequestAndGroups(Request $request,array $group)
    {
        $data = $this->getQueryWithRequest(
            self::DATATABLE_SCALAR_RESULT,
            $request->get('columns',[]),
            $request->get('start',0),
            $request->get('length',10)
        );
        foreach ($group as $propName => $id){
            $data->andWhere($propName.'.id = :'.$propName.'Id')
                ->setParameter($propName.'Id',$id);
        }
        $data = $data->getQuery()->getSingleColumnResult();
        return reset($data);
    }

    public function getCountByGroups(array $group)
    {
        $data = $this->getQuery()
            ->select("COUNT(".$this->alias.".id)");

        foreach ($group as $propName => $id){
            if(!$this->hasQueryJoin($data,$propName)){
                $data->join($this->alias.'.'.$propName,$propName);
            }
            $data->andWhere($propName.'.id = :'.$propName.'Id')
                ->setParameter($propName.'Id',$id);
        }
        $data = $data->getQuery()->getSingleColumnResult();
        return reset($data);
    }
    /* end - dataTable request queries by multi group */

    /* start - export table base on request queries */
    public function getResultWithRequest($columns = null,$orders = null){
        $select = "";
        $groupBy = "";

        if($columns){
            $columns = array_map(function ($item){
                $item['column'] = call_user_func_array(array($this->entityClass, 'getDataTableColumn'), array($item['data']));
                return $item;
            },$columns);
            $columns = array_filter($columns,function ($item){
                return $item['data']!='action';
            });
        }

        $b = $this->getQuery();
        $entities = [];
        // add select fields
        foreach ($columns as $col) {
            //nejbo data d column
            $column = $col['column'];
            //nexof wax entity ola column 3adi
            if(array_key_exists('entity',$column)){
                $colMetadata = $this->entityManager->getClassMetadata($column['entity']);
                if(array_key_exists('field',$column)){
                    $field = $column['field'];
                }
                else{
                    $field = array_values($colMetadata->getFieldNames())[1];
                }
                $select .= $column['mapped'].'.'.$field.' AS '.$column['data'].',';
                if(!in_array($column['mapped'],$entities)){
                    $entities[] = $column['mapped'];
                    if(!$this->hasQueryJoin($b,$column['mapped'])){
                        $b->join($this->alias.'.'.$column['mapped'],$column['mapped']);
                    }
                }
            }
            else{
                $select .= $this->alias.'.'.$col['data'].',';
            }
        }
        // add order
        if($orders){
            foreach ($orders as $order){
                $column = $columns[$order['column']]['column'];
                //nexof wax entity ola column 3adi
                if(array_key_exists('entity',$column)){
                    $b->orderBy($column['data'], $order['dir']);
                }
                else{
                    $b->orderBy($this->alias . '.' . $columns[$order['column']]['data'], $order['dir']);
                }
            }
        }

        //delete last ',' from query
        if(str_ends_with($select, ',')){ $select = substr_replace($select ,"", -1); }
        if($select){ $b->select($select); }
        if(str_ends_with($groupBy, ',')){ $groupBy = substr_replace($groupBy ,"", -1); }
        if($groupBy){ $b->groupBy($groupBy); }

        // add search in column if exists
        if($columns){
            foreach ($columns as $col){
                if($col['search']['value']){
                    $column = $col['column'];
                    $el = $col['data'];
                    $val = $col['search']['value'];
                    //nexof wax entity ola column 3adi
                    if(array_key_exists('entity',$column)){
                        $b->andWhere($column['mapped'].'.id = :searchBy'.$el)
                            ->setParameter('searchBy'.$el,$val);
                    }
                    else{
                        // ila knan search b boolean
                        if($val=="false" || $val=="true"){
                            $b->andWhere($this->alias.'.'.$el.' = '.$val);
                        }
                        //ila kna search 3adi text
                        else{
                            $b->andWhere($this->alias.'.'.$el.' LIKE :searchBy'.$el)
                                ->setParameter('searchBy'.$el,'%'.$val.'%');
                        }
                    }
                }
            }
        }
        return $b;
    }

    public function getTableResultWithRequest($columns = null,$orders = null)
    {
        $b = $this->getResultWithRequest($columns,$orders);
        return $b->getQuery()->getArrayResult();
    }
    /* end - export table base on request queries */
}
