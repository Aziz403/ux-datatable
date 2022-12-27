<?php

namespace Aziz403\UX\Datatable\Column;

class DateColumn extends AbstractColumn
{
    private ?string $format;
    private $render;

    public function __construct(string $field,?string $format = "Y-m-d",?string $displayName = null,bool $visible = true,bool $orderable = true,?callable $render = null)
    {
        parent::__construct($field,$displayName,$visible,$orderable);
        $this->format = $format;
        $this->render = $render;
    }

    public function render($entity,$value) :string
    {
        //check if has custom render condition
        if($this->render && is_callable($this->render)){
            return call_user_func($this->render,$value);
        }
        //else render using format
        return $value->format($this->format);
    }
}