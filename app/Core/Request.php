<?php

namespace App\Core;

class Request
{
    protected $method;
    protected $uri;
    protected $params;
    protected $query;
    protected $body;
    protected $files;
    protected $headers;
    
    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->uri = $this->parseUri();
        $this->params = $_REQUEST;
        $this->query = $_GET;
        $this->body = $_POST;
        $this->files = $_FILES;
        $this->headers = $this->parseHeaders();
        
        // Parse JSON body
        if ($this->getHeader('Content-Type') === 'application/json') {
            $json = file_get_contents('php://input');
            $this->body = json_decode($json, true) ?? [];
        }
    }
    
    protected function parseUri()
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        
        // Remove query string
        if (($pos = strpos($uri, '?')) !== false) {
            $uri = substr($uri, 0, $pos);
        }
        
        return '/' . trim($uri, '/');
    }
    
    protected function parseHeaders()
    {
        $headers = [];
        foreach ($_SERVER as $key => $value) {
            if (strpos($key, 'HTTP_') === 0) {
                $header = str_replace('HTTP_', '', $key);
                $header = str_replace('_', '-', $header);
                $headers[$header] = $value;
            }
        }
        return $headers;
    }
    
    public function getMethod()
    {
        return $this->method;
    }
    
    public function getUri()
    {
        return $this->uri;
    }
    
    public function get($key, $default = null)
    {
        return $this->params[$key] ?? $default;
    }
    
    public function all()
    {
        return $this->params;
    }
    
    public function input($key, $default = null)
    {
        return $this->body[$key] ?? $default;
    }
    
    public function query($key, $default = null)
    {
        return $this->query[$key] ?? $default;
    }
    
    public function file($key)
    {
        return $this->files[$key] ?? null;
    }
    
    public function hasFile($key)
    {
        return isset($this->files[$key]) && $this->files[$key]['error'] === UPLOAD_ERR_OK;
    }
    
    public function getHeader($key)
    {
        return $this->headers[$key] ?? null;
    }
    
    public function isAjax()
    {
        return $this->getHeader('X-Requested-With') === 'XMLHttpRequest';
    }
    
    public function isJson()
    {
        return strpos($this->getHeader('Content-Type') ?? '', 'application/json') !== false;
    }
    
    public function ip()
    {
        return $_SERVER['REMOTE_ADDR'] ?? null;
    }
    
    public function userAgent()
    {
        return $_SERVER['HTTP_USER_AGENT'] ?? null;
    }
}
