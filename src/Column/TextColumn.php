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
class TextColumn extends AbstractColumn
{
    private $render;

    public function __construct(string $field,?string $displayName = null,bool $visible = true,bool $orderable = true,$render = null)
    {
        parent::__construct($field,$displayName,$orderable,$visible);
        $this->render = $render;
    }

    public function render($entity,$value) :string
    {
        //check if has custom render condition
        if($this->render && is_callable($this->render)){
            return call_user_func($this->render,$value);
        }
        //return the same result
        return $value;
    }
}