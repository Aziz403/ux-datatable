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
                $column->setEnvironment($this->environment);
                $value = $column->render($item,$value);
                $row[$index] = "$value";
            }
            $result[] = $row;
        }
        return $result;
    }
}