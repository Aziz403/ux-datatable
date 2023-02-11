<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Aziz Benmallouk <azizbenmallouk4@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Aziz403\UX\Datatable\Event;

use Aziz403\UX\Datatable\Model\EntityDatatable;
use Doctrine\ORM\QueryBuilder;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * @author Aziz Benmallouk <azizbenmallouk4@gmail.com>
 */
final class RenderSearchQueryEvent extends Event
{
    private EntityDatatable $datatable;

    private QueryBuilder $query;

    /**
     * @internal
     */
    public function __construct(EntityDatatable $datatable,QueryBuilder $query) {
        $this->datatable = $datatable;
        $this->query = $query;
    }

    /**
     * Get the value of query
     */ 
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Get the value of datatable
     */ 
    public function getDatatable()
    {
        return $this->datatable;
    }
}
