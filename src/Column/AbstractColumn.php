<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Aziz Benmallouk <azizbenmallouk4@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Aziz403\UX\Datatable\Column;

/**
 * @author Aziz Benmallouk <azizbenmallouk4@gmail.com>
 */
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

    public function __toString()
    {
        return $this->data;
    }
}