<?php

namespace App\Helpers;

class StringHelper
{
    public static function slug($string)
    {
        $string = strtolower($string);
        $string = preg_replace('/[^a-z0-9-]/', '-', $string);
        $string = preg_replace('/-+/', '-', $string);
        return trim($string, '-');
    }
    
    public static function truncate($string, $length = 100, $suffix = '...')
    {
        if (strlen($string) <= $length) {
            return $string;
        }
        
        return substr($string, 0, $length) . $suffix;
    }
    
    public static function excerpt($text, $length = 200)
    {
        $text = strip_tags($text);
        return self::truncate($text, $length);
    }
    
    public static function randomString($length = 32)
    {
        return bin2hex(random_bytes($length / 2));
    }
    
    public static function mask($string, $visibleChars = 4)
    {
        $length = strlen($string);
        
        if ($length <= $visibleChars) {
            return $string;
        }
        
        $masked = str_repeat('*', $length - $visibleChars);
        return $masked . substr($string, -$visibleChars);
    }
    
    public static function camelCase($string)
    {
        return lcfirst(str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $string))));
    }
    
    public static function snakeCase($string)
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $string));
    }
}
