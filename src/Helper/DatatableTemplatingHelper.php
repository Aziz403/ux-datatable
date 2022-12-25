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
use Aziz403\UX\Datatable\Column\BooleanColumn;
use Aziz403\UX\Datatable\Column\CustomColumn;
use Aziz403\UX\Datatable\Column\DateColumn;
use Aziz403\UX\Datatable\Column\EntityColumn;
use Aziz403\UX\Datatable\Column\InlineTwigColumn;
use Aziz403\UX\Datatable\Column\TextColumn;
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
                //get column index and value
                $index = $this->datatable->getColumnIndex($column->getData());
                $getMethodName = "";
                $isMethodName = "";
                $getMethod = [$item,"get".ucfirst($column)];
                $isMethod = [$item,"is".ucfirst($column)];
                if(is_callable($getMethod,false,$getMethodName)){
                    $value = call_user_func_array($getMethod,[]);
                }
                elseif(is_callable($isMethod,false,$isMethodName)){
                    $value = call_user_func_array($isMethod,[]);
                }
                elseif(!$column instanceof TwigColumn && !$column instanceof InlineTwigColumn){
                    throw new \Exception("Not Found Getter For $column, With Name $getMethodName or $isMethodName");
                }

                //add special parts to each column value base on column type
                if($column instanceof BadgeColumn){
                    $color = $column->getColor($value);
                    $value = "<span class='datatable-badge' style='background-color: $color'>$value</span>";
                }
                elseif($column instanceof DateColumn){
                    $value = $value->format($column->getFormat());
                }
                elseif($column instanceof BooleanColumn){
                    $value = $column->getResult($value);
                }
                elseif($column instanceof EntityColumn){
                    if($value){
                        $funcName = $column->getField() ? "get".ucfirst($column->getField()) : "__toString";
                        $method = [$value,$funcName];
                        if(is_callable($method))
                        $value = call_user_func_array($method,[]);
                    }
                }
                elseif($column instanceof TwigColumn){
                    $value = $this->environment->render($column->getTemplate(),[
                        'entity' => $item
                    ]);
                }
                elseif($column instanceof InlineTwigColumn){
                    $value = $this->environment->render("@Datatable/column/inline_twig.html.twig",[
                        'inline_template' => $column->getTemplate(),
                        'entity' => $item
                    ]);
                }
                $row[$index] = $value;
            }
            $result[] = $row;
        }
        return $result;
    }
}