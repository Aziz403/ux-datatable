<?php

namespace Aziz403\UX\Datatable\Column;

class TextColumn extends AbstractColumn
{

    public function __construct(string $field,?string $display = null,bool $visible = true,bool $orderable = true)
    {
        $this->data = $field;
        $this->text = $display ?? $field;
        $this->visible = $visible;
        $this->orderable = $orderable;
    }

    public function build()
    {
        return [
            'data' => $this->data,
            'text' => $this->text,
            'visible' => $this->visible,
            'orderable' => $this->orderable
        ];
    }
}