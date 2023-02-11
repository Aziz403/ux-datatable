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
use Symfony\Contracts\EventDispatcher\Event;

/**
 * @author Aziz Benmallouk <azizbenmallouk4@gmail.com>
 */
final class RenderQueryEvent extends Event
{
    private EntityDatatable $datatable;

    private array $query = [];
    
    private int $recordsTotal = 0;
    private int $recordsFiltered = 0;
    private iterable $records = [];

    /**
     * @internal
     */
    public function __construct(EntityDatatable $datatable,array $query) {
        $this->datatable = $datatable;
        $this->query = $query;
    }

    /**
     * Get the value of recordsTotal
     */ 
    public function getRecordsTotal()
    {
        return $this->recordsTotal;
    }

    /**
     * Set the value of recordsTotal
     *
     * @return  self
     */ 
    public function setRecordsTotal($recordsTotal)
    {
        $this->recordsTotal = $recordsTotal;

        return $this;
    }

    /**
     * Get the value of recordsFiltered
     */ 
    public function getRecordsFiltered()
    {
        return $this->recordsFiltered;
    }

    /**
     * Set the value of recordsFiltered
     *
     * @return  self
     */ 
    public function setRecordsFiltered($recordsFiltered)
    {
        $this->recordsFiltered = $recordsFiltered;

        return $this;
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
