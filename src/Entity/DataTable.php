<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Aziz Benmallouk <azizbenmallouk4@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\UX\Datatable\Entity;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @author Aziz Benmallouk <azizbenmallouk4@gmail.com>
 *
 * @final
 */
abstract class DataTable implements DataTableInterface
{
    public static function getDataTableColumns(UrlGeneratorInterface $router){
        return array_map(function ($item) use ($router){
            if(isset($item['rowgroup'])){
                if($item['rowgroup']=='api' && isset($item['ajax'])){
                    $item['ajax'] = $router->generate($item['ajax'],$item['params']);
                }
            }
            return $item;
        },static::getFields());
    }

    public static function getDataTableColumn($dataName){
        $arr = array_filter(static::getFields(),function ($item) use ($dataName){
            return ($item['data']==$dataName) ? $item : null;
        });
        return reset($arr);
    }

    public static function simpleColumn(string $displayName,bool $defualtVisibility = true,string $format = null,string $searchFormat = self::SEARCH_FORMAT_SIMPLE)
    {
        return array(
            'data'=>$displayName,
            'visible'=>$defualtVisibility,
            'format'=>$format,
            'rowgroup'=>$searchFormat
        );
    }

    public static function selectColumn(string $displayName,array $values,bool $defualtVisibility = true,string $format = null,string $searchFormat = self::SEARCH_FORMAT_STATIC)
    {
        return array(
            'data'=>$displayName,
            'rowgroup' => $searchFormat,
            'values' => $values,
            'format'=>$format,
            'visible'=>$defualtVisibility,
        );
    }

    public static function entityColumn(string $displayName,string $mappedName,string $className,string $entity,string $displayField,string $dataEntityPath = null,bool $defualtVisibility = true,string $format = null,string $searchFormat = self::SEARCH_FORMAT_API)
    {
        $data = array(
            'data' => $displayName,
            'mapped' => $mappedName,
            'entity' => $className,
            'params'=>[
                'class'=>$entity,
                'displayName'=>$displayField
            ],
            'format'=>$format,
            'visible'=>$defualtVisibility,
        );
        $data['rowgroup'] = $searchFormat;
        if($searchFormat==self::SEARCH_FORMAT_API){
            $data['ajax'] = $dataEntityPath;
        }
        return $data;
    }

    public static function actionColumn()
    {
        return array(
            'data' => 'action',
            'format' => 'action',
            'orderable' => false,
            'width' => '115px'
        );
    }
}