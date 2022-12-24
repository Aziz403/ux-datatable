<?php

namespace Aziz403\UX\Datatable\Column;

use Twig\Environment;

abstract class AbstractColumn
{
    protected string $data;
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

    /**
     * @return string
     */
    public function getData(): string
    {
        return $this->data;
    }
}