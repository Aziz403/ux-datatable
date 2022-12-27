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

use Aziz403\UX\Datatable\Service\DataService;
use Doctrine\ORM\QueryBuilder;

/**
 * @author Aziz Benmallouk <azizbenmallouk4@gmail.com>
 */
class EntityColumn extends AbstractColumn
{
    const ENTITY_INNER_JOIN = "ENTITY_INNER_JOIN";
    const ENTITY_LEFT_JOIN = "ENTITY_LEFT_JOIN";

    private string $entity;
    private ?string $field;
    private ?string $nullValue;
    private $render;
    private string $joinType;

    public function __construct(string $entity,?string $field = null,?string $displayName = null,$render = null,?string $nullValue = null,string $joinType = self::ENTITY_LEFT_JOIN,bool $visible = true,bool $orderable = true,bool $searchable = true)
    {
        parent::__construct($entity,$displayName,$visible,$orderable,$searchable,true);
        $this->entity = $entity;
        $this->field = $field;
        $this->nullValue = $nullValue;
        $this->render = $render;
        $this->joinType = $joinType;
    }

    public function render($entity,$value) :?string
    {
        if(!$value){
            return "$this->nullValue";
        }
        //check if has custom render condition
        if($this->render && is_callable($this->render)){
            return call_user_func($this->render,$value);
        }
        //else check if has specific field to display
        if($this->field){
            DataService::getPropValue($value,$this->field);
        }
        //return __toString result
        return "$value";
    }

    public function search(QueryBuilder $builder, string $query): QueryBuilder
    {
        return $builder;
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

    /**
     * @return string
     */
    public function getJoinType(): string
    {
        return $this->joinType;
    }
}