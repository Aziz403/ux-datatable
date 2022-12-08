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
class Datatable extends AbstractDatatable
{
    public function __construct()
    {
        parent::__construct(self::SIMPLE_DATATABLE_CONFIG);
    }

    public function createView(): array
    {
        return [
            'options' => $this->options
        ];
    }
}
