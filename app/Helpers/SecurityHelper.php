<?php

namespace App\Helpers;

class SecurityHelper
{
    public static function encrypt($data, $key = null)
    {
        $key = $key ?? config('app.encryption_key');
        $cipher = 'AES-256-CBC';
        
        $ivLength = openssl_cipher_iv_length($cipher);
        $iv = openssl_random_pseudo_bytes($ivLength);
        
        $encrypted = openssl_encrypt($data, $cipher, $key, 0, $iv);
        
        return base64_encode($encrypted . '::' . $iv);
    }
    
    public static function decrypt($data, $key = null)
    {
        $key = $key ?? config('app.encryption_key');
        $cipher = 'AES-256-CBC';
        
        list($encrypted, $iv) = explode('::', base64_decode($data), 2);
        
        return openssl_decrypt($encrypted, $cipher, $key, 0, $iv);
    }
    
    public static function hash($data)
    {
        return hash('sha256', $data);
    }
    
    public static function generateToken($length = 32)
    {
        return bin2hex(random_bytes($length));
    }
    
    public static function sanitizeInput($input)
    {
        if (is_array($input)) {
            return array_map([self::class, 'sanitizeInput'], $input);
        }
        
        return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    }
    
    public static function preventXSS($string)
    {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
}
