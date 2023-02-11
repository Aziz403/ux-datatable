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

use Aziz403\UX\Datatable\Model\AbstractDatatable;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * @author Aziz Benmallouk <azizbenmallouk4@gmail.com>
 */
final class RenderDataEvent extends Event
{
    private AbstractDatatable $datatable;
    private iterable $records = [];

    /**
     * @internal
     */
    public function __construct(AbstractDatatable $datatable,iterable $records) {
        $this->datatable = $datatable;
        $this->records = $records;
    }

    /**
     * Get the value of records
     */ 
    public function getRecords()
    {
        return $this->records;
    }

    /**
     * Set the value of records
     *
     * @return  self
     */ 
    public function setRecords($records)
    {
        $this->records = $records;

        return $this;
    }

    /**
     * Get the value of datatable
     */ 
    public function getDatatable()
    {
        return $this->datatable;
    }
}
