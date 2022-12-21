<?php

namespace Aziz403\UX\Datatable\Columns;

class EntityColumn extends AbstractColumn
{
    private string $entity;
    private string $field;

    public function __construct(string $entity,string $field,string $display = null,bool $visible = true,bool $orderable = true)
    {
        $this->data = $entity.'.'.$field;
        $this->entity = $entity;
        $this->field = $field;
        $this->text = $display ?? $this->data;
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