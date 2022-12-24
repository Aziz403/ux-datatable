<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Aziz Benmallouk <azizbenmallouk4@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Aziz403\UX\Datatable\Builder;

use Aziz403\UX\Datatable\Model\EntityDatatable;

/**
 * @author Aziz Benmallouk <azizbenmallouk4@gmail.com>
 */
interface DatatableBuilderInterface
{
    public function createDatatableFromEntity(string $className): EntityDatatable;
}