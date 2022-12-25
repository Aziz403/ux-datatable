<?php

namespace Aziz403\UX\Datatable\Column;

class BooleanColumn extends AbstractColumn
{
    private string $trueResult;
    private string $falseResult;

    public function __construct(string $field,string $trueResult = "Yes",string $falseResult = "No",string $display = '',bool $visible = true,bool $orderable = true)
    {
        $this->data = $field;
        $this->trueResult = $trueResult;
        $this->falseResult = $falseResult;
        $this->text = $display ?? $field;
        $this->visible = $visible;
        $this->orderable = $orderable;
    }

    public function getResult(?bool $value) :string
    {
        return $value ? $this->trueResult : $this->falseResult;
    }

    public function build()
    {
        // TODO: Implement build() method.
    }
}