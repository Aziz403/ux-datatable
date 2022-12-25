<?php

namespace Aziz403\UX\Datatable\Column;

class DateColumn extends AbstractColumn
{
    private string $format;

    public function __construct(string $field,string $format = "Y-m-d",string $display = '',bool $visible = true,bool $orderable = true)
    {
        $this->data = $field;
        $this->format = $format;
        $this->text = $display ?? $field;
        $this->visible = $visible;
        $this->orderable = $orderable;
    }

    /**
     * @return string
     */
    public function getFormat(): string
    {
        return $this->format;
    }

    public function build()
    {
        // TODO: Implement build() method.
    }
}