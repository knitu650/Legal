<?php

namespace App\Models;

use App\Core\Model;

class Role extends Model
{
    protected $table = 'roles';
    protected $fillable = ['name', 'slug', 'description'];
    
    public function users()
    {
        $userModel = new User();
        return $userModel->where('role_id', $this->id)->get();
    }
    
    public function permissions()
    {
        $db = $this->db;
        $stmt = $db->prepare("
            SELECT p.* FROM permissions p
            INNER JOIN role_permissions rp ON p.id = rp.permission_id
            WHERE rp.role_id = ?
        ");
        $stmt->execute([$this->id]);
        return $stmt->fetchAll();
    }
    
    public function hasPermission($permissionSlug)
    {
        $permissions = $this->permissions();
        foreach ($permissions as $permission) {
            if ($permission->slug === $permissionSlug) {
                return true;
            }
        }
        return false;
    }
}
