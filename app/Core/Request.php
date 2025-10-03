<?php
namespace App\Core;

class Request {
    protected $get;
    protected $post;
    protected $server;
    protected $files;
    protected $cookies;
    protected $headers;

    public function __construct() {
        $this->get = $_GET;
        $this->post = $_POST;
        $this->server = $_SERVER;
        $this->files = $_FILES;
        $this->cookies = $_COOKIE;
        $this->headers = $this->getRequestHeaders();
    }

    protected function getRequestHeaders() {
        $headers = [];
        foreach ($_SERVER as $key => $value) {
            if (strpos($key, 'HTTP_') === 0) {
                $header = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))));
                $headers[$header] = $value;
            }
        }
        return $headers;
    }

    public function getMethod() {
        return strtoupper($this->server['REQUEST_METHOD']);
    }

    public function isGet() {
        return $this->getMethod() === 'GET';
    }

    public function isPost() {
        return $this->getMethod() === 'POST';
    }

    public function isPut() {
        return $this->getMethod() === 'PUT';
    }

    public function isDelete() {
        return $this->getMethod() === 'DELETE';
    }

    public function isAjax() {
        return isset($this->headers['X-Requested-With']) && 
               $this->headers['X-Requested-With'] === 'XMLHttpRequest';
    }

    public function getPath() {
        $path = $this->server['REQUEST_URI'] ?? '/';
        $position = strpos($path, '?');
        if ($position !== false) {
            $path = substr($path, 0, $position);
        }
        return $path;
    }

    public function get($key = null, $default = null) {
        if ($key === null) {
            return $this->get;
        }
        return $this->get[$key] ?? $default;
    }

    public function post($key = null, $default = null) {
        if ($key === null) {
            return $this->post;
        }
        return $this->post[$key] ?? $default;
    }

    public function input($key = null, $default = null) {
        $data = array_merge($this->get, $this->post);
        if ($key === null) {
            return $data;
        }
        return $data[$key] ?? $default;
    }

    public function file($key) {
        return $this->files[$key] ?? null;
    }

    public function hasFile($key) {
        return isset($this->files[$key]) && $this->files[$key]['error'] !== UPLOAD_ERR_NO_FILE;
    }

    public function cookie($key = null, $default = null) {
        if ($key === null) {
            return $this->cookies;
        }
        return $this->cookies[$key] ?? $default;
    }

    public function header($key = null, $default = null) {
        if ($key === null) {
            return $this->headers;
        }
        return $this->headers[$key] ?? $default;
    }

    public function ip() {
        return $this->server['REMOTE_ADDR'];
    }

    public function userAgent() {
        return $this->server['HTTP_USER_AGENT'] ?? '';
    }

    public function all() {
        return array_merge($this->get, $this->post);
    }

    public function only($keys) {
        $keys = is_array($keys) ? $keys : func_get_args();
        $results = [];

        foreach ($keys as $key) {
            if (array_key_exists($key, $this->input())) {
                $results[$key] = $this->input($key);
            }
        }

        return $results;
    }

    public function except($keys) {
        $keys = is_array($keys) ? $keys : func_get_args();
        $results = $this->all();

        foreach ($keys as $key) {
            unset($results[$key]);
        }

        return $results;
    }

    public function validate($rules) {
        $validator = new Validator($this->all(), $rules);
        return $validator->validate();
    }
}