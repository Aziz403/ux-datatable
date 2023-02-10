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

use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

/**
 * @author Aziz Benmallouk <azizbenmallouk4@gmail.com>
 */
class ArrayDatatable extends AbstractDatatable
{
    protected string $locale;

    protected array $data = [];

    public function __construct(
        Environment $environment,
        TranslatorInterface $translator,
        array $config,
        array $data,
        string $locale
    )
    {
        parent::__construct($environment,$translator,$config);
        $this->data = $data;
        $this->locale = $locale;
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
     * @return string
     */
    public function getLanguage(): string
    {
        if($this->language==null || $this->language=='request'){
            $this->language = $this->locale;
        }

        return $this->language;
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
                    "data" => $this->data
                ]
            ),
        ];
    }
}
