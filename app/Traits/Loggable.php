<?php

namespace App\Traits;

use App\Models\AuditLog;

trait Loggable
{
    protected function logAction($action, $modelId, $oldValues = [], $newValues = [])
    {
        $userId = null;
        
        if (function_exists('auth') && auth()) {
            $userId = auth()->id;
        }
        
        AuditLog::log(
            $userId,
            $action,
            get_class($this),
            $modelId,
            $oldValues,
            $newValues
        );
    }
    
    public function create($data)
    {
        $result = parent::create($data);
        
        if ($result) {
            $this->logAction('create', $result->id, [], $data);
        }
        
        return $result;
    }
    
    public function update($id, $data)
    {
        $old = $this->find($id);
        $result = parent::update($id, $data);
        
        if ($result && $old) {
            $this->logAction('update', $id, (array)$old, $data);
        }
        
        return $result;
    }
    
    public function delete($id)
    {
        $old = $this->find($id);
        $result = parent::delete($id);
        
        if ($result && $old) {
            $this->logAction('delete', $id, (array)$old, []);
        }
        
        return $result;
    }
}
