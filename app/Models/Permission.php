<?php

namespace App\Models;

use App\Core\Model;

class Permission extends Model
{
    protected $table = 'permissions';
    protected $fillable = ['name', 'slug', 'description'];
    
    public function roles()
    {
        $db = $this->db;
        $stmt = $db->prepare("
            SELECT r.* FROM roles r
            INNER JOIN role_permissions rp ON r.id = rp.role_id
            WHERE rp.permission_id = ?
        ");
        $stmt->execute([$this->id]);
        return $stmt->fetchAll();
    }
    
    public function assignToRole($roleId)
    {
        $stmt = $this->db->prepare("
            INSERT INTO role_permissions (role_id, permission_id) 
            VALUES (?, ?)
            ON DUPLICATE KEY UPDATE role_id = role_id
        ");
        return $stmt->execute([$roleId, $this->id]);
    }
    
    public function removeFromRole($roleId)
    {
        $stmt = $this->db->prepare("
            DELETE FROM role_permissions 
            WHERE role_id = ? AND permission_id = ?
        ");
        return $stmt->execute([$roleId, $this->id]);
    }
}
