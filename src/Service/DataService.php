<?php

namespace Aziz403\UX\Datatable\Service;

use Aziz403\UX\Datatable\Exception\GetterNotFoundException;

class DataService
{
    public static function getPropValue(object $object,string $property,array $argements = [])
    {
        $class = \get_class($object);

        $methods = get_class_methods($object);
        $method = array_filter($methods,function ($method) use($property){
            if ('g' === $method[0] && 0 === strpos($method, 'get')) {
                $name = substr($method, 3);
            } elseif ('i' === $method[0] && 0 === strpos($method, 'is')) {
                $name = substr($method, 2);
            }

            if(isset($name) && lcfirst($name)===$property){
                return $method;
            }

            if($method===$property){
                return $method;
            }
        });

        if (count($method)>0) {
            $method = array_values($method)[0];
        } else {
            throw new \Exception(sprintf('Neither the property "%1$s" nor one of the methods "%1$s()", "get%1$s()"/"is%1$s()"/"has%1$s()" or "__call()" exist and have public access in class "%2$s".', $property, $class));
        }

        return $object->$method(...$argements);
    }
}