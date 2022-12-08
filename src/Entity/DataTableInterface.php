<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Aziz Benmallouk <azizbenmallouk4@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Aziz403\UX\Datatable\Entity;

/**
 * @author Aziz Benmallouk <azizbenmallouk4@gmail.com>
 *
 * @final
 */
interface DataTableInterface
{
    const COLUMN_FORMAT_MONEY = 'price';
    const COLUMN_FORMAT_DATE = 'date';
    const COLUMN_FORMAT_BUDGE = 'budge';
    const COLUMN_FORMAT_ARRAY = 'array';

    const SEARCH_FORMAT_SIMPLE = 'simple';
    const SEARCH_FORMAT_DATE = 'date';
    const SEARCH_FORMAT_API = 'api';
    const SEARCH_FORMAT_ENTITY_FIELD = 'field';
    const SEARCH_FORMAT_STATIC = 'static';
    const SEARCH_FORMAT_INT_BETWEEN = 'int_between';
    const SEARCH_FORMAT_DATE_BETWEEN = 'date_between';

    public static function getFields ():array;
}