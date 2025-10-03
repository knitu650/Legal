<?php

namespace App\Utils;

class Cache
{
    protected $cachePath;
    protected $ttl = 3600; // 1 hour default
    
    public function __construct()
    {
        $this->cachePath = storage_path('cache/data/');
        
        if (!is_dir($this->cachePath)) {
            mkdir($this->cachePath, 0755, true);
        }
    }
    
    public function get($key, $default = null)
    {
        $file = $this->getFilePath($key);
        
        if (!file_exists($file)) {
            return $default;
        }
        
        $data = unserialize(file_get_contents($file));
        
        // Check if expired
        if (isset($data['expires_at']) && time() > $data['expires_at']) {
            $this->forget($key);
            return $default;
        }
        
        return $data['value'] ?? $default;
    }
    
    public function set($key, $value, $ttl = null)
    {
        $ttl = $ttl ?? $this->ttl;
        
        $data = [
            'value' => $value,
            'expires_at' => time() + $ttl
        ];
        
        file_put_contents($this->getFilePath($key), serialize($data));
        
        return true;
    }
    
    public function has($key)
    {
        return $this->get($key) !== null;
    }
    
    public function forget($key)
    {
        $file = $this->getFilePath($key);
        
        if (file_exists($file)) {
            return unlink($file);
        }
        
        return false;
    }
    
    public function flush()
    {
        $files = glob($this->cachePath . '*');
        
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
        
        return true;
    }
    
    public function remember($key, $ttl, $callback)
    {
        $value = $this->get($key);
        
        if ($value !== null) {
            return $value;
        }
        
        $value = $callback();
        $this->set($key, $value, $ttl);
        
        return $value;
    }
    
    protected function getFilePath($key)
    {
        return $this->cachePath . md5($key) . '.cache';
    }
}
