<?php

namespace Aziz403\UX\Datatable\Column;

class InlineTwigColumn extends AbstractColumn
{
    private string $template;

    public function __construct(string $field,string $template,string $display = null, $visible = true,bool $orderable = true)
    {
        $this->data = $field;
        $this->template = $template;
        $this->text = $display ?? $field;
        $this->visible = $visible;
        $this->orderable = $orderable;
    }

    /**
     * @return string
     */
    public function getTemplate(): string
    {
        return $this->template;
    }

    public function build()
    {
        // TODO: Implement build() method.
    }
}