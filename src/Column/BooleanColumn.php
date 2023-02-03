<?php

namespace Aziz403\UX\Datatable\Column;

use Doctrine\ORM\Query\Expr\Comparison;
use Doctrine\ORM\QueryBuilder;

class BooleanColumn extends AbstractColumn
{
    private string $trueResult;
    private string $falseResult;
    private $render;

    public function __construct(string $field,string $trueResult = "Yes",string $falseResult = "No",?string $displayName = null,bool $visible = true,bool $orderable = true,?callable $render = null,bool $searchable = true)
    {
        parent::__construct($field,$displayName,$visible,$orderable,$searchable,true);
        $this->trueResult = $trueResult;
        $this->falseResult = $falseResult;
        $this->render = $render;
    }

    public function render($entity,$value) :string
    {
        //check if has custom render condition
        if($this->render && is_callable($this->render)){
            return call_user_func($this->render,[$entity,$value]);
        }
        //return the same result
        return $value ? $this->trueResult : $this->falseResult;
    }

    public function search(QueryBuilder $builder, string $query): ?Comparison
    {
        //get root alias
        $alias = $builder->getRootAliases()[0];

        //generate unique key for search
        $key = "search_$this".rand(9,999);

        //where in bool
        $expr = $builder->expr()->eq("$alias.$this->data",":$key");
        if($query=='true'||$query==$this->trueResult){
            $param = true;
        }
        elseif($query=='false'||$query==$this->falseResult){
            $param = false;
        }
        else{
            return null;
        }
        $builder->setParameter($key,$param);
        return $expr;
    }
}