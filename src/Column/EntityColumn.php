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
class EntityColumn extends AbstractColumn
{
    private string $entity;
    private ?string $field;
    private ?string $nullValue;
    private $render;

    public function __construct(string $entity,?string $field = null,?string $display = null,$render = null,?string $nullValue = null,bool $visible = true,bool $orderable = true)
    {
        $this->data = $entity;
        $this->entity = $entity;
        $this->field = $field;
        $this->text = $display ?? $this->data;
        $this->visible = $visible;
        $this->orderable = $orderable;
        $this->nullValue = $nullValue;
        $this->render = $render;
    }

    public function render($entity) :?string
    {
        if(!$entity){
            return "$this->nullValue";
        }
        //check if has custom render condition
        if($this->render && is_callable($this->render)){
            return call_user_func($this->render,$entity);
        }
        //else check if has specific field to display
        if($this->field){
            $method = [$entity,"get".ucfirst($this->field)];
            if(is_callable($method)){
                return call_user_func_array($method,[]);
            }
        }
        //return __toString result
        return "$entity";
    }

    /**
     * @return string
     */
    public function getEntity(): string
    {
        return $this->entity;
    }

    /**
     * @return string|null
     */
    public function getField(): ?string
    {
        return $this->field;
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