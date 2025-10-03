<?php

namespace App\Traits;

use App\Utils\Cache;

trait Cacheable
{
    protected $cache;
    
    protected function getCacheKey($identifier)
    {
        return $this->table . '_' . $identifier;
    }
    
    public function remember($key, $ttl, $callback)
    {
        if (!$this->cache) {
            $this->cache = new Cache();
        }
        
        return $this->cache->remember($this->getCacheKey($key), $ttl, $callback);
    }
    
    public function clearCache($key)
    {
        if (!$this->cache) {
            $this->cache = new Cache();
        }
        
        return $this->cache->forget($this->getCacheKey($key));
    }
    
    public function find($id)
    {
        return $this->remember('find_' . $id, 3600, function() use ($id) {
            $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ? LIMIT 1");
            $stmt->execute([$id]);
            return $stmt->fetch();
        });
    }
}
