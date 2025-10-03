<?php

use App\Core\Session;

if (!function_exists('config')) {
    function config($key, $default = null) {
        $keys = explode('.', $key);
        $file = array_shift($keys);
        
        $configPath = __DIR__ . '/../../config/' . $file . '.php';
        if (!file_exists($configPath)) {
            return $default;
        }
        
        $config = require $configPath;
        
        foreach ($keys as $k) {
            if (!isset($config[$k])) {
                return $default;
            }
            $config = $config[$k];
        }
        
        return $config;
    }
}

if (!function_exists('env')) {
    function env($key, $default = null) {
        return $_ENV[$key] ?? $default;
    }
}

if (!function_exists('asset')) {
    function asset($path) {
        return '/assets/' . ltrim($path, '/');
    }
}

if (!function_exists('url')) {
    function url($path = '') {
        $baseUrl = rtrim(config('app.url', 'http://localhost'), '/');
        return $baseUrl . '/' . ltrim($path, '/');
    }
}

if (!function_exists('redirect')) {
    function redirect($path) {
        header('Location: ' . url($path));
        exit;
    }
}

if (!function_exists('old')) {
    function old($key, $default = '') {
        return Session::get('_old_' . $key, $default);
    }
}

if (!function_exists('session')) {
    function session($key = null, $default = null) {
        if ($key === null) {
            return new Session();
        }
        return Session::get($key, $default);
    }
}

if (!function_exists('flash')) {
    function flash($key, $value = null) {
        return Session::flash($key, $value);
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token() {
        if (!Session::has('_csrf_token')) {
            Session::set('_csrf_token', bin2hex(random_bytes(32)));
        }
        return Session::get('_csrf_token');
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field() {
        return '<input type="hidden" name="_csrf_token" value="' . csrf_token() . '">';
    }
}

if (!function_exists('auth')) {
    function auth() {
        return Session::get('user');
    }
}

if (!function_exists('dd')) {
    function dd(...$vars) {
        foreach ($vars as $var) {
            echo '<pre>';
            var_dump($var);
            echo '</pre>';
        }
        die();
    }
}

if (!function_exists('sanitize')) {
    function sanitize($data) {
        if (is_array($data)) {
            return array_map('sanitize', $data);
        }
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('currency')) {
    function currency($amount) {
        $symbol = config('locations.currency_symbol', '₹');
        return $symbol . number_format($amount, 2);
    }
}

if (!function_exists('date_format_custom')) {
    function date_format_custom($date, $format = 'Y-m-d H:i:s') {
        return date($format, strtotime($date));
    }
}

if (!function_exists('storage_path')) {
    function storage_path($path = '') {
        return __DIR__ . '/../../storage/' . ltrim($path, '/');
    }
}

if (!function_exists('public_path')) {
    function public_path($path = '') {
        return __DIR__ . '/../../public/' . ltrim($path, '/');
    }
}

if (!function_exists('base_path')) {
    function base_path($path = '') {
        return __DIR__ . '/../../' . ltrim($path, '/');
    }
}
