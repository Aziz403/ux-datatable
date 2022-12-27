<?php

namespace Aziz403\UX\Datatable\Column;

class BooleanColumn extends AbstractColumn
{
    private string $trueResult;
    private string $falseResult;
    private $render;

    public function __construct(string $field,string $trueResult = "Yes",string $falseResult = "No",?string $displayName = null,bool $visible = true,bool $orderable = true,?callable $render = null)
    {
        parent::__construct($field,$displayName,$visible,$orderable);
        $this->trueResult = $trueResult;
        $this->falseResult = $falseResult;
        $this->render = $render;
    }

    public function render($entity,$value) :string
    {
        //check if has custom render condition
        if($this->render && is_callable($this->render)){
            return call_user_func($this->render,$value);
        }
        //return the same result
        return $value ? $this->trueResult : $this->falseResult;
    }
}