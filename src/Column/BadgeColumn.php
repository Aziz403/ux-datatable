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
class BadgeColumn extends AbstractColumn
{
    const COLOR_DEFAULT = 'default';
    const COLOR_PRIMARY = 'primary';
    const COLOR_SECONDARY = 'secondary';
    const COLOR_SUCCESS = 'success';
    const COLOR_INFO = 'info';
    const COLOR_DANGER = 'danger';

    private $condition;
    private string $trueColor;
    private string $falseColor;

    public function __construct(string $field,string $trueColor = self::COLOR_PRIMARY,string $falseColor = self::COLOR_DEFAULT,?callable $condition = null,string $display = '',bool $visible = true,bool $orderable = true)
    {
        $this->data = $field;
        $this->condition = $condition;
        $this->trueColor = $trueColor;
        $this->falseColor = $falseColor;
        $this->text = $display ?? $field;
        $this->visible = $visible;
        $this->orderable = $orderable;
    }

    public function getColor(string $value) :string
    {
        //get badge color base on condition
        if($this->condition && is_callable($this->condition)){
            return call_user_func($this->condition,$value);
        }
        //get badge color from true&false colors
        return $value ? $this->trueColor : $this->falseColor;
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