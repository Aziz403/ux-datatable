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
class TwigColumn extends AbstractColumn
{
    private string $template;
    private array $params;

    public function __construct(string $field,string $template,array $params = [],?string $displayName = null, $visible = true)
    {
        parent::__construct($field, $displayName, $visible, false, false,false);
        $this->template = $template;
        $this->params = $params;
    }

    public function render($entity,$value) :string
    {
        return $this->environment->render($this->template,array_merge(['entity'=>$entity],$this->params));
    }

    public function search(QueryBuilder $builder, string $query): ?Comparison
    {
        return null;
    }
}