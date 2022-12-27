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

use Twig\Environment;

/**
 * @author Aziz Benmallouk <azizbenmallouk4@gmail.com>
 */
abstract class AbstractColumn
{
    protected string $data;
    protected string $text;
    protected bool $visible;
    protected bool $orderable;

    protected Environment $environment;

    public function __construct(string $data,?string $text,bool $visible,bool $orderable)
    {
        $this->data = $data;
        $this->text = $text ?? $data;
        $this->visible = $visible;
        $this->orderable = $orderable;
    }

    abstract public function render($entity,$value);

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

    /**
     * Used inside render method in TwigColumn to render view
     * @param Environment $environment
     */
    public function setEnvironment(Environment $environment): void
    {
        $this->environment = $environment;
    }

    public function __toString()
    {
        return $this->data;
    }
}