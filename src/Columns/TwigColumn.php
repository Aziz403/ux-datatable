<?php

namespace Aziz403\UX\Datatable\Columns;

class TwigColumn extends AbstractColumn
{
    private string $data;
    private string $template;

    public function __construct(string $field,string $template,string $display = null, $visible = true,bool $orderable = true)
    {
        $this->data = $field;
        $this->template = $template;
        $this->text = $display ?? $field;
        $this->visible = $visible;
        $this->orderable = $orderable;
    }

    public function build()
    {
        return [
            'data' => $this->data,
            'template' => $this->template,
            'text' => $this->text,
            'visible' => $this->visible,
            'orderable' => $this->orderable
        ];
    }
}