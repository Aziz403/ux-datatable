<?php

namespace Aziz403\UX\Datatable\Column;

class DateColumn extends AbstractColumn
{
    private ?string $format;
    private $render;

    public function __construct(string $field,?string $format = "Y-m-d",?string $display = null,bool $visible = true,bool $orderable = true,?callable $render = null)
    {
        $this->data = $field;
        $this->format = $format;
        $this->text = $display ?? $field;
        $this->visible = $visible;
        $this->orderable = $orderable;
        $this->render = $render;
    }

    public function render($value) :string
    {
        //check if has custom render condition
        if($this->render && is_callable($this->render)){
            return call_user_func($this->render,$value);
        }
        //else render using format
        return $value->format($this->format);
    }

    public function build()
    {
        // TODO: Implement build() method.
    }
}