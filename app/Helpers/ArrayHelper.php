<?php

namespace App\Helpers;

class ArrayHelper
{
    public static function get($array, $key, $default = null)
    {
        if (is_null($key)) {
            return $array;
        }
        
        if (isset($array[$key])) {
            return $array[$key];
        }
        
        foreach (explode('.', $key) as $segment) {
            if (!is_array($array) || !array_key_exists($segment, $array)) {
                return $default;
            }
            
            $array = $array[$segment];
        }
        
        return $array;
    }
    
    public static function pluck($array, $key)
    {
        return array_map(function($item) use ($key) {
            return is_object($item) ? $item->$key : $item[$key];
        }, $array);
    }
    
    public static function only($array, $keys)
    {
        return array_intersect_key($array, array_flip($keys));
    }
    
    public static function except($array, $keys)
    {
        return array_diff_key($array, array_flip($keys));
    }
    
    public static function groupBy($array, $key)
    {
        $result = [];
        
        foreach ($array as $item) {
            $groupKey = is_object($item) ? $item->$key : $item[$key];
            $result[$groupKey][] = $item;
        }
        
        return $result;
    }
}
