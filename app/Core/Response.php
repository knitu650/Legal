<?php

namespace App\Core;

class Response
{
    protected $statusCode = 200;
    protected $headers = [];
    protected $content;
    
    public function setStatusCode($code)
    {
        $this->statusCode = $code;
        http_response_code($code);
        return $this;
    }
    
    public function setHeader($key, $value)
    {
        $this->headers[$key] = $value;
        header("$key: $value");
        return $this;
    }
    
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }
    
    public function send()
    {
        echo $this->content;
        return $this;
    }
    
    public function json($data, $statusCode = 200)
    {
        $this->setStatusCode($statusCode);
        $this->setHeader('Content-Type', 'application/json');
        echo json_encode($data);
        return $this;
    }
    
    public function redirect($url, $statusCode = 302)
    {
        $this->setStatusCode($statusCode);
        $this->setHeader('Location', $url);
        exit;
    }
}
