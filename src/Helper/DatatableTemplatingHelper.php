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
        $twigColumns = $this->datatable->getTwigColumns();

        foreach ($data as $row){
            foreach ($row as $cell=>$value){
                $columnInfo = $this->datatable->getColumn($cell);

                if($columnInfo instanceof BadgeColumn) {
                    $color = $columnInfo->getColor($value);
                    if(str_starts_with($color,"#")){
                        $value = "<span class='datatable-badge' style='background-color: $color'>$value</span>";
                    }
                    else{
                        $value = "<span class='datatable-badge badge-$color'>$value</span>";
                    }
                }
                //replace item name by index in the data var
                $row[$this->datatable->getColumnIndex($columnInfo->getData())] = $value;
            }

            //add twig columns if exists
            foreach ($twigColumns as $columnInfo){
                $result = $this->environment->render($columnInfo->getTemplate(),[$row]);
                $row[$this->datatable->getColumnIndex($columnInfo->getData())] = $result;
            }

            $renderData[] = $row;
        }
        return $renderData;
    }
}