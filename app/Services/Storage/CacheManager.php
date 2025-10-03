<?php

namespace App\Services\Storage;

class CacheManager
{
    protected $cachePath;
    protected $defaultTTL = 3600; // 1 hour
    
    public function __construct()
    {
        $this->cachePath = storage_path('cache/data/');
        
        if (!is_dir($this->cachePath)) {
            mkdir($this->cachePath, 0755, true);
        }
    }
    
    /**
     * Get cached value
     */
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
    
    /**
     * Set cached value
     */
    public function set($key, $value, $ttl = null)
    {
        $ttl = $ttl ?? $this->defaultTTL;
        
        $data = [
            'value' => $value,
            'expires_at' => time() + $ttl,
            'created_at' => time()
        ];
        
        return file_put_contents(
            $this->getFilePath($key), 
            serialize($data),
            LOCK_EX
        ) !== false;
    }
    
    /**
     * Check if key exists and not expired
     */
    public function has($key)
    {
        return $this->get($key) !== null;
    }
    
    /**
     * Delete cached value
     */
    public function forget($key)
    {
        $file = $this->getFilePath($key);
        
        if (file_exists($file)) {
            return unlink($file);
        }
        
        return false;
    }
    
    /**
     * Clear all cache
     */
    public function flush()
    {
        $files = glob($this->cachePath . '*');
        $deleted = 0;
        
        foreach ($files as $file) {
            if (is_file($file)) {
                if (unlink($file)) {
                    $deleted++;
                }
            }
        }
        
        return $deleted;
    }
    
    /**
     * Remember (get or set)
     */
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
    
    /**
     * Remember forever (no expiration)
     */
    public function rememberForever($key, $callback)
    {
        return $this->remember($key, 315360000, $callback); // 10 years
    }
    
    /**
     * Increment cached value
     */
    public function increment($key, $amount = 1)
    {
        $value = (int)$this->get($key, 0);
        $value += $amount;
        $this->set($key, $value);
        
        return $value;
    }
    
    /**
     * Decrement cached value
     */
    public function decrement($key, $amount = 1)
    {
        return $this->increment($key, -$amount);
    }
    
    /**
     * Cache views (compiled templates)
     */
    public function cacheView($viewName, $compiledContent)
    {
        $viewCachePath = storage_path('cache/views/');
        
        if (!is_dir($viewCachePath)) {
            mkdir($viewCachePath, 0755, true);
        }
        
        $filename = md5($viewName) . '.php';
        
        return file_put_contents(
            $viewCachePath . $filename,
            $compiledContent,
            LOCK_EX
        ) !== false;
    }
    
    /**
     * Get cached view
     */
    public function getCachedView($viewName)
    {
        $viewCachePath = storage_path('cache/views/');
        $filename = md5($viewName) . '.php';
        $filePath = $viewCachePath . $filename;
        
        if (file_exists($filePath)) {
            return $filePath;
        }
        
        return null;
    }
    
    /**
     * Cache routes
     */
    public function cacheRoutes($routes)
    {
        $routeCachePath = storage_path('cache/routes/');
        
        if (!is_dir($routeCachePath)) {
            mkdir($routeCachePath, 0755, true);
        }
        
        return file_put_contents(
            $routeCachePath . 'routes.cache',
            serialize($routes),
            LOCK_EX
        ) !== false;
    }
    
    /**
     * Get cached routes
     */
    public function getCachedRoutes()
    {
        $routeCachePath = storage_path('cache/routes/');
        $filePath = $routeCachePath . 'routes.cache';
        
        if (file_exists($filePath)) {
            return unserialize(file_get_contents($filePath));
        }
        
        return null;
    }
    
    /**
     * Clean expired cache entries
     */
    public function cleanExpired()
    {
        $files = glob($this->cachePath . '*');
        $cleaned = 0;
        
        foreach ($files as $file) {
            if (!is_file($file)) {
                continue;
            }
            
            $data = unserialize(file_get_contents($file));
            
            if (isset($data['expires_at']) && time() > $data['expires_at']) {
                if (unlink($file)) {
                    $cleaned++;
                }
            }
        }
        
        return $cleaned;
    }
    
    /**
     * Get cache statistics
     */
    public function getStats()
    {
        $files = glob($this->cachePath . '*');
        $totalSize = 0;
        $expired = 0;
        $active = 0;
        
        foreach ($files as $file) {
            if (!is_file($file)) {
                continue;
            }
            
            $totalSize += filesize($file);
            
            $data = unserialize(file_get_contents($file));
            
            if (isset($data['expires_at']) && time() > $data['expires_at']) {
                $expired++;
            } else {
                $active++;
            }
        }
        
        return [
            'total_entries' => count($files),
            'active_entries' => $active,
            'expired_entries' => $expired,
            'total_size' => $totalSize,
            'total_size_formatted' => $this->formatBytes($totalSize)
        ];
    }
    
    protected function getFilePath($key)
    {
        return $this->cachePath . md5($key) . '.cache';
    }
    
    protected function formatBytes($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }
}
