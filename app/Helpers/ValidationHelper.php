<?php

namespace App\Helpers;

class ValidationHelper
{
    public static function isEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    public static function isPhone($phone)
    {
        return preg_match('/^[0-9]{10}$/', $phone);
    }
    
    public static function isUrl($url)
    {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }
    
    public static function isAlphaNumeric($string)
    {
        return preg_match('/^[a-zA-Z0-9]+$/', $string);
    }
    
    public static function isAlpha($string)
    {
        return preg_match('/^[a-zA-Z]+$/', $string);
    }
    
    public static function isNumeric($value)
    {
        return is_numeric($value);
    }
    
    public static function isStrongPassword($password)
    {
        // At least 8 characters, 1 uppercase, 1 lowercase, 1 number
        return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $password);
    }
    
    public static function isPincode($pincode)
    {
        return preg_match('/^[0-9]{6}$/', $pincode);
    }
}
