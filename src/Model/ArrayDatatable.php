<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Aziz Benmallouk <azizbenmallouk4@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Aziz403\UX\Datatable\Model;

use Aziz403\UX\Datatable\Event\Events;
use Aziz403\UX\Datatable\Event\RenderDataEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author Aziz Benmallouk <azizbenmallouk4@gmail.com>
 */
class ArrayDatatable extends AbstractDatatable
{
    protected array $data = [];

    public function __construct(
        EventDispatcherInterface $dispatcher,
        TranslatorInterface $translator,
        array $config,
        string $locale,
        array $data,
    )
    {
        parent::__construct($dispatcher,$translator,$config,$locale);
        $this->data = $data;
    }

    /**
     * @return array|null
     */
    public function getData(): ?array
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData(array $data): void
    {
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function getColumnsView(): array
    {
        $fieldName = 'title';
        if((array() === $this->data) || (array_keys($this->data) !== range(0, count($this->data) - 1))){
            $fieldName = 'data';
        }

        $columns = [];
        foreach($this->columns as $column){
            $columns[] = [
                $fieldName => $column->getData()
            ];
        }
        return $columns;
    }

    public function renderData()
    {
        $event = new RenderDataEvent($this,$this->data);
        $this->dispatcher->dispatch($event,Events::PRE_DATA);

        return $event->getRecords();
    }

    /**
     * @return array
     */
    public function createView(): array
    {
        return [
            "options" => array_merge(
                $this->options,
                [
                    "columns" => $this->getColumnsView(),
                    "columnDefs" => $this->getColumnDefs(),
                    "language" => $this->getLanguageData(),
                    "data" => $this->renderData()
                ]
            ),
        ];
    }
}
