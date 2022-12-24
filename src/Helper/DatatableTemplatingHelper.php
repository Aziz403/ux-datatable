<?php

namespace Aziz403\UX\Datatable\Helper;

use Aziz403\UX\Datatable\Model\EntityDatatable;
use Twig\Environment;

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
    public function renderData(array $data):array
    {
        $renderData = [];
        foreach ($data as $row){
            //$column = $this->datatable->getColumn($row);
        }
        return $renderData;
    }
}