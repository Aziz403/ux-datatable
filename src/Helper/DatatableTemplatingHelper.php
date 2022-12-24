<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Aziz Benmallouk <azizbenmallouk4@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Aziz403\UX\Datatable\Helper;

use Aziz403\UX\Datatable\Column\BadgeColumn;
use Aziz403\UX\Datatable\Column\TwigColumn;
use Aziz403\UX\Datatable\Model\EntityDatatable;
use Twig\Environment;

/**
 * @author Aziz Benmallouk <azizbenmallouk4@gmail.com>
 */
class DatatableTemplatingHelper
{
    private Environment $environment;
    private EntityDatatable $datatable;

    public function __construct(Environment $environment,EntityDatatable $datatable)
    {
        $this->environment = $environment;
        $this->datatable = $datatable;
    }

    /**
     * @param array $data
     * @return array
     */
    public function renderData(array $data) :array
    {
        $renderData = [];
        foreach ($data as $row){
            $columnInfo = $this->datatable->getColumn($row['data']);
            if($columnInfo instanceof TwigColumn) {

            }
            elseif($columnInfo instanceof BadgeColumn) {

            }
            //dd($row);
        }
        return $renderData;
    }
}