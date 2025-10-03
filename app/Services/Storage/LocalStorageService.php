<?php

namespace App\Services\Storage;

class LocalStorageService
{
    protected $basePath;
    
    public function __construct()
    {
        $this->basePath = storage_path('app/');
    }
    
    public function store($file, $path = '')
    {
        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            return false;
        }
        
        $directory = $this->basePath . $path;
        
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
        
        $filename = time() . '_' . $file['name'];
        $destination = $directory . '/' . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return $path . '/' . $filename;
        }
        
        return false;
    }
    
    public function delete($path)
    {
        $fullPath = $this->basePath . $path;
        
        if (file_exists($fullPath)) {
            return unlink($fullPath);
        }
        
        return false;
    }
    
    public function get($path)
    {
        $fullPath = $this->basePath . $path;
        
        if (file_exists($fullPath)) {
            return file_get_contents($fullPath);
        }
        
        return null;
    }
    
    public function exists($path)
    {
        return file_exists($this->basePath . $path);
    }
    
    public function url($path)
    {
        return asset('storage/' . $path);
    }
    
    public function size($path)
    {
        $fullPath = $this->basePath . $path;
        
        if (file_exists($fullPath)) {
            return filesize($fullPath);
        }
        
        return 0;
    }
}
