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

use Aziz403\UX\Datatable\Model\AbstractDatatable;
use Aziz403\UX\Datatable\Model\ArrayDatatable;
use Aziz403\UX\Datatable\Model\EntityDatatable;
use Aziz403\UX\Datatable\Service\DataService;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Twig\Environment;

/**
 * @author Aziz Benmallouk <azizbenmallouk4@gmail.com>
 */
class DatatableTemplatingHelper
{
    private Environment $environment;
    private PropertyAccessorInterface $propertyAccessor;
    private AbstractDatatable $datatable;
    
    private iterable $data;

    public function __construct(Environment $environment,PropertyAccessorInterface $propertyAccessor,AbstractDatatable $datatable,iterable $data)
    {
        $this->environment = $environment;
        $this->propertyAccessor = $propertyAccessor;
        $this->datatable = $datatable;
        $this->data = $data;
    }

    /**
     * convert data from array of entity objects to datatable result
     * @return array
     */
    public function renderData() :array
    {
        $result = [];
        foreach ($this->data as $item){
            $row = [];
            foreach ($this->datatable->getColumns() as $column){
                //get column index
                $index = $this->datatable->getColumnIndex($column->getData());
                $value = null;

                //get value from prop if column mapped on entity
                if($this->datatable instanceof EntityDatatable && $column->isMapped()) {
                    $value = $this->propertyAccessor->getValue($item,$column->__toString());
                }
                else if($this->datatable instanceof ArrayDatatable){
                    $value = $item[$index];
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