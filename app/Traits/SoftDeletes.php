<?php

namespace App\Traits;

trait SoftDeletes
{
    public function delete($id)
    {
        // Soft delete - set deleted_at timestamp
        return $this->update($id, [
            'deleted_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    public function forceDelete($id)
    {
        // Permanently delete
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?");
        return $stmt->execute([$id]);
    }
    
    public function restore($id)
    {
        return $this->update($id, [
            'deleted_at' => null
        ]);
    }
    
    public function withTrashed()
    {
        // Include soft deleted records
        return $this;
    }
    
    public function onlyTrashed()
    {
        return $this->where('deleted_at', 'IS NOT', null);
    }
    
    public function all()
    {
        // Override to exclude soft deleted
        $stmt = $this->db->query("SELECT * FROM {$this->table} WHERE deleted_at IS NULL");
        return $stmt->fetchAll();
    }
}
