<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Role;
use App\Models\Permission;

class RolePermissionController extends Controller
{
    public function index()
    {
        $roleModel = new Role();
        $roles = $roleModel->all();
        
        $this->view('admin/roles/index', [
            'pageTitle' => 'Roles & Permissions',
            'roles' => $roles
        ]);
    }
    
    public function store()
    {
        $data = [
            'name' => $this->request->input('name'),
            'slug' => $this->request->input('slug'),
            'description' => $this->request->input('description'),
        ];
        
        $roleModel = new Role();
        $roleModel->create($data);
        
        flash('success', 'Role created successfully!');
        $this->redirect('/admin/roles');
    }
    
    public function permissions($id)
    {
        $roleModel = new Role();
        $role = $roleModel->find($id);
        
        if (!$role) {
            flash('error', 'Role not found.');
            $this->redirect('/admin/roles');
            return;
        }
        
        $permissionModel = new Permission();
        $allPermissions = $permissionModel->all();
        $rolePermissions = $role->permissions();
        
        $this->view('admin/roles/permissions', [
            'pageTitle' => 'Manage Permissions',
            'role' => $role,
            'allPermissions' => $allPermissions,
            'rolePermissions' => $rolePermissions
        ]);
    }
    
    public function updatePermissions($id)
    {
        $roleModel = new Role();
        $role = $roleModel->find($id);
        
        if (!$role) {
            $this->json(['error' => 'Role not found'], 404);
            return;
        }
        
        $permissions = $this->request->input('permissions', []);
        
        // Remove all existing permissions
        $db = $roleModel->db;
        $db->prepare("DELETE FROM role_permissions WHERE role_id = ?")->execute([$id]);
        
        // Add new permissions
        foreach ($permissions as $permissionId) {
            $db->prepare("INSERT INTO role_permissions (role_id, permission_id) VALUES (?, ?)")
               ->execute([$id, $permissionId]);
        }
        
        flash('success', 'Permissions updated successfully!');
        $this->redirect('/admin/roles/' . $id . '/permissions');
    }
}
