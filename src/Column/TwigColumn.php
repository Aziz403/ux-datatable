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
class TwigColumn extends AbstractColumn
{
    private string $template;

    public function __construct(string $field,string $template,?string $displayName = null, $visible = true,bool $orderable = true)
    {
        parent::__construct($field, $displayName, $visible, $orderable);
        $this->template = $template;
    }

    public function render($entity,$value) :string
    {
        return $this->environment->render($this->template,[
            'entity' => $entity
        ]);
    }
}