<?php

namespace Aziz403\UX\Datatable\Service;

use Aziz403\UX\Datatable\Exception\GetterNotFoundException;

class DataService
{
    public static function getPropValue($object,$propName)
    {
        $getType = [$object,"get".ucfirst($propName)];
        $isType = [$object,"is".ucfirst($propName)];
        $hasType = [$object,"has".ucfirst($propName)];
        if(is_callable($getType)){
            $value = call_user_func_array($getType,[]);
        }
        elseif(is_callable($isType)){
            $value = call_user_func_array($isType,[]);
        }
        elseif(is_callable($hasType)){
            $value = call_user_func_array($hasType,[]);
        }
        else{
            throw new GetterNotFoundException("Not Found Getter For $propName, With Name $getType[1],$isType[1] or $hasType[1]");
        }
        return $value;
    }
}