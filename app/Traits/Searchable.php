<?php

namespace App\Traits;

trait Searchable
{
    public function search($query, $fields = [])
    {
        if (empty($fields)) {
            $fields = $this->searchable ?? ['name', 'title'];
        }
        
        $sql = "SELECT * FROM {$this->table} WHERE ";
        $conditions = [];
        $bindings = [];
        
        foreach ($fields as $field) {
            $conditions[] = "$field LIKE ?";
            $bindings[] = "%$query%";
        }
        
        $sql .= implode(' OR ', $conditions);
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($bindings);
        
        return $stmt->fetchAll();
    }
    
    public function scopeSearch($query)
    {
        $this->searchQuery = $query;
        return $this;
    }
}
