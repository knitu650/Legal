<?php

namespace App\Services\Storage;

class FileStorageService
{
    protected $driver;
    
    public function __construct()
    {
        $driver = config('storage.default', 'local');
        
        switch ($driver) {
            case 's3':
                $this->driver = new S3Service();
                break;
            case 'cloudinary':
                $this->driver = new CloudinaryService();
                break;
            default:
                $this->driver = new LocalStorageService();
        }
    }
    
    public function store($file, $path = '')
    {
        return $this->driver->store($file, $path);
    }
    
    public function delete($path)
    {
        return $this->driver->delete($path);
    }
    
    public function get($path)
    {
        return $this->driver->get($path);
    }
    
    public function exists($path)
    {
        return $this->driver->exists($path);
    }
    
    public function url($path)
    {
        return $this->driver->url($path);
    }
}
