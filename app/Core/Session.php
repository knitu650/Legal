<?php

namespace App\Core;

class Session
{
    public static function start()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    public static function set($key, $value)
    {
        self::start();
        $_SESSION[$key] = $value;
    }
    
    public static function get($key, $default = null)
    {
        self::start();
        return $_SESSION[$key] ?? $default;
    }
    
    public static function has($key)
    {
        self::start();
        return isset($_SESSION[$key]);
    }
    
    public static function remove($key)
    {
        self::start();
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }
    
    public static function flush()
    {
        self::start();
        $_SESSION = [];
    }
    
    public static function destroy()
    {
        self::start();
        session_destroy();
    }
    
    public static function flash($key, $value = null)
    {
        self::start();
        
        if ($value === null) {
            // Get flash message
            $flashKey = "_flash_$key";
            $message = $_SESSION[$flashKey] ?? null;
            unset($_SESSION[$flashKey]);
            return $message;
        }
        
        // Set flash message
        $_SESSION["_flash_$key"] = $value;
    }
    
    public static function regenerate()
    {
        self::start();
        session_regenerate_id(true);
    }
}
