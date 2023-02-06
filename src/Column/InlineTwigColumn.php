<?php

namespace Aziz403\UX\Datatable\Column;

use Doctrine\ORM\Query\Expr\Comparison;
use Doctrine\ORM\QueryBuilder;

class InlineTwigColumn extends AbstractColumn
{
    private string $template;
    private array $params;

    public function __construct(string $field,string $template,array $params = [],?string $displayName = null, $visible = true)
    {
        parent::__construct($field,$displayName,$visible,false,false,false);
        $this->template = $template;
        $this->params = $params;
    }

    public function render($entity, $value)
    {
        return $this->environment
            ->createTemplate($this->template)
            ->render([
            'entity' => $entity,
            ...$this->params
            ])
        ;
    }

    public function search(QueryBuilder $builder, string $query): ?Comparison
    {
        return null;
    }
}