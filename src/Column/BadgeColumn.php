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

/**
 * @author Aziz Benmallouk <azizbenmallouk4@gmail.com>
 */
class BadgeColumn extends AbstractColumn
{
    const COLOR_DEFAULT = 'default';
    const COLOR_PRIMARY = 'primary';
    const COLOR_SECONDARY = 'secondary';
    const COLOR_SUCCESS = 'success';
    const COLOR_INFO = 'info';
    const COLOR_DANGER = 'danger';

    private $render;
    private string $trueColor;
    private string $falseColor;

    public function __construct(string $field,string $trueColor = self::COLOR_PRIMARY,string $falseColor = self::COLOR_DEFAULT,?callable $render = null,?string $displayName = null,bool $visible = true,bool $orderable = true,bool $searchable = true)
    {
        parent::__construct($field,$displayName,$visible,$orderable,$searchable,true);
        $this->trueColor = $trueColor;
        $this->falseColor = $falseColor;
        $this->render = $render;
    }

    public function render($entity,$value) :string
    {
        if($this->render && is_callable($this->render)){
            //get badge color base on condition
            $color = call_user_func($this->render,[$entity,$value]);
        }
        else{
            //get badge color from true&false colors
            $color = $value ? $this->trueColor : $this->falseColor;
        }
        return "<span class='datatable-badge' style='background-color: $color'>$value</span>";
    }

    public function search(QueryBuilder $builder, string $query): Comparison
    {
        //get root alias
        $alias = $builder->getRootAliases()[0];

        //where in badge text
        return $builder->expr()->like("$alias.$this->data","'%$query%'");
    }
}