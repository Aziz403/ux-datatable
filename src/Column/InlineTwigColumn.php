<?php

namespace Aziz403\UX\Datatable\Column;

class InlineTwigColumn extends AbstractColumn
{
    private string $template;

    public function __construct(string $field,string $template,?string $displayName = null, $visible = true,bool $orderable = true)
    {
        parent::__construct($field,$displayName,$visible,$orderable);
        $this->template = $template;
    }

    public function render($entity, $value)
    {
        return $this->environment->render("@Datatable/column/inline_twig.html.twig",[
            'inline_template' => $this->template,
            'entity' => $entity
        ]);
    }
}