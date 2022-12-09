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

/**
 * @author Aziz Benmallouk <azizbenmallouk4@gmail.com>
 *
 * @final
 */
class ComplexDatatable extends AbstractDatatable
{
    private string $path;
    private string $locale;
    private array $columns;

    public function __construct(string $tableId,string $path,array $columns,string $locale = self::LOCALE_FROM_SESSION)
    {
        parent::__construct(self::COMPLEX_DATATABLE_CONFIG,$tableId);
        $this->path = $path;
        $this->columns = $columns;
        $this->locale = $locale;

    }

    public function createView(): array
    {
        return [
            'path' => $this->path,
            'columns' => $this->columns,
            'locale' => $this->locale,
            'options' => $this->options,
        ];
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getLocale(): string
    {
        return $this->locale;
    }

    /**
     * @param string $locale
     */
    public function setLocale(string $locale): void
    {
        $this->locale = $locale;
    }

    /**
     * @return array
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * @param array $columns
     */
    public function setColumns(array $columns): void
    {
        $this->columns = $columns;
    }
}
