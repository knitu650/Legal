<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\User;
use App\Models\Role;

class UserManagementController extends Controller
{
    public function index()
    {
        $userModel = new User();
        $search = $this->request->query('search');
        $role = $this->request->query('role');
        
        $query = $userModel;
        
        if ($search) {
            $query = $query->where('name', 'LIKE', "%$search%");
        }
        
        if ($role) {
            $query = $query->where('role_id', $role);
        }
        
        $users = $query->orderBy('created_at', 'DESC')->get();
        
        $roleModel = new Role();
        $roles = $roleModel->all();
        
        $this->view('admin/users/index', [
            'pageTitle' => 'User Management',
            'users' => $users,
            'roles' => $roles
        ]);
    }
    
    public function create()
    {
        $roleModel = new Role();
        $roles = $roleModel->all();
        
        $this->view('admin/users/create', [
            'pageTitle' => 'Create User',
            'roles' => $roles
        ]);
    }
    
    public function store()
    {
        $data = [
            'name' => $this->request->input('name'),
            'email' => $this->request->input('email'),
            'phone' => $this->request->input('phone'),
            'password' => $this->request->input('password'),
            'role_id' => $this->request->input('role_id'),
            'status' => $this->request->input('status', 'active'),
        ];
        
        $isValid = $this->validate($data, [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role_id' => 'required|numeric',
        ]);
        
        if (!$isValid) {
            flash('error', 'Please fill all fields correctly.');
            $this->back();
            return;
        }
        
        $userModel = new User();
        $userModel->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => password_hash($data['password'], PASSWORD_BCRYPT),
            'role_id' => $data['role_id'],
            'status' => $data['status'],
            'email_verified_at' => date('Y-m-d H:i:s')
        ]);
        
        flash('success', 'User created successfully!');
        $this->redirect('/admin/users');
    }
    
    public function show($id)
    {
        $userModel = new User();
        $user = $userModel->find($id);
        
        if (!$user) {
            flash('error', 'User not found.');
            $this->redirect('/admin/users');
            return;
        }
        
        $this->view('admin/users/view', [
            'pageTitle' => 'User Details',
            'user' => $user
        ]);
    }
    
    public function edit($id)
    {
        $userModel = new User();
        $user = $userModel->find($id);
        
        if (!$user) {
            flash('error', 'User not found.');
            $this->redirect('/admin/users');
            return;
        }
        
        $roleModel = new Role();
        $roles = $roleModel->all();
        
        $this->view('admin/users/edit', [
            'pageTitle' => 'Edit User',
            'user' => $user,
            'roles' => $roles
        ]);
    }
    
    public function update($id)
    {
        $userModel = new User();
        $user = $userModel->find($id);
        
        if (!$user) {
            flash('error', 'User not found.');
            $this->redirect('/admin/users');
            return;
        }
        
        $data = [
            'name' => $this->request->input('name'),
            'phone' => $this->request->input('phone'),
            'role_id' => $this->request->input('role_id'),
            'status' => $this->request->input('status'),
        ];
        
        $userModel->update($id, $data);
        
        flash('success', 'User updated successfully!');
        $this->redirect('/admin/users/' . $id);
    }
    
    public function delete($id)
    {
        $userModel = new User();
        $userModel->delete($id);
        
        flash('success', 'User deleted successfully!');
        $this->redirect('/admin/users');
    }
}
