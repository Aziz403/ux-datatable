<?php

namespace Aziz403\UX\Datatable\Column;

use Doctrine\ORM\QueryBuilder;

class InlineTwigColumn extends AbstractColumn
{
    private string $template;

    public function __construct(string $field,string $template,?string $displayName = null, $visible = true)
    {
        parent::__construct($field,$displayName,$visible,false,false,false);
        $this->template = $template;
    }

    public function render($entity, $value)
    {
        return $this->environment->render("@Datatable/column/inline_twig.html.twig",[
            'inline_template' => $this->template,
            'entity' => $entity
        ]);
    }

    public function search(QueryBuilder $builder, string $query): QueryBuilder
    {
        return $builder;
    }
}