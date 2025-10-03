<?php

namespace App\Models;

use App\Core\Model;

class AuditLog extends Model
{
    protected $table = 'audit_logs';
    protected $fillable = [
        'user_id', 'action', 'model', 'model_id', 
        'old_values', 'new_values', 'ip_address', 'user_agent'
    ];
    
    public $timestamps = false;
    
    public function user()
    {
        $userModel = new User();
        return $userModel->find($this->user_id);
    }
    
    public function getOldValues()
    {
        return json_decode($this->old_values, true) ?? [];
    }
    
    public function getNewValues()
    {
        return json_decode($this->new_values, true) ?? [];
    }
    
    public static function log($userId, $action, $model, $modelId, $oldValues = [], $newValues = [])
    {
        $instance = new self();
        return $instance->create([
            'user_id' => $userId,
            'action' => $action,
            'model' => $model,
            'model_id' => $modelId,
            'old_values' => json_encode($oldValues),
            'new_values' => json_encode($newValues),
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null
        ]);
    }
    
    public function getChanges()
    {
        $old = $this->getOldValues();
        $new = $this->getNewValues();
        
        $changes = [];
        foreach ($new as $key => $value) {
            if (!isset($old[$key]) || $old[$key] != $value) {
                $changes[$key] = [
                    'old' => $old[$key] ?? null,
                    'new' => $value
                ];
            }
        }
        
        return $changes;
    }
}
