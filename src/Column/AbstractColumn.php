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

use Doctrine\ORM\Query\Expr\Comparison;
use Doctrine\ORM\QueryBuilder;
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
    protected bool $searchable;
    protected bool $mapped;

    protected Environment $environment;

    public function __construct(string $data,?string $text,bool $visible,bool $orderable,bool $searchable,bool $mapped)
    {
        $this->data = $data;
        $this->text = $text ?? $data;
        $this->visible = $visible;
        $this->orderable = $orderable;
        $this->searchable = $searchable;
        $this->mapped = $mapped;
    }

    abstract public function render($entity,$value);

    abstract public function search(QueryBuilder $builder,string $query) :?Comparison;

    public function order(QueryBuilder $builder,string $dir) :QueryBuilder
    {
        if($this->orderable){
            $alias = $builder->getRootAliases()[0];
            $builder->addOrderBy("$alias.$this",$dir);
        }
        return $builder;
    }

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
     * @return bool
     */
    public function isSearchable(): bool
    {
        return $this->searchable;
    }

    /**
     * @return bool
     */
    public function isVisible(): bool
    {
        return $this->visible;
    }

    /**
     * @return bool
     */
    public function isMapped(): bool
    {
        return $this->mapped;
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