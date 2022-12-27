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

use Aziz403\UX\Datatable\Model\EntityDatatable;
use Aziz403\UX\Datatable\Service\DataService;
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
     * convert data from array of entity objects to datatable result
     * @param array $data
     * @return array
     */
    public function renderData(array $data) :array
    {
        $result = [];

        foreach ($data as $item){
            $row = [];
            foreach ($this->datatable->getColumns() as $column){
                //get column index
                $index = $this->datatable->getColumnIndex($column->getData());
                $value = null;

                //get value from prop if column mapped on entity
                if($column->isMapped()) {
                    DataService::getPropValue($item,$column);
                }

                //add special parts to each column value base on column type
                $column->setEnvironment($this->environment);
                $value = $column->render($item,$value);
                $row[$index] = "$value";
            }
            $result[] = $row;
        }
        return $result;
    }
}