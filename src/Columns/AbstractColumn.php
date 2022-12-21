<?php

namespace Aziz403\UX\Datatable\Columns;

use Twig\Environment;

abstract class AbstractColumn
{
    protected string $text;
    protected bool $visible;
    protected bool $orderable;

    abstract public function build();

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }
}