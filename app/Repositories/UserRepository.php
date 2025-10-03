<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    protected $model;
    
    public function __construct()
    {
        $this->model = new User();
    }
    
    public function findById($id)
    {
        return $this->model->find($id);
    }
    
    public function findByEmail($email)
    {
        return User::findByEmail($email);
    }
    
    public function getAll($filters = [])
    {
        $query = $this->model;
        
        if (isset($filters['role_id'])) {
            $query = $query->where('role_id', $filters['role_id']);
        }
        
        if (isset($filters['status'])) {
            $query = $query->where('status', $filters['status']);
        }
        
        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query = $query->where('name', 'LIKE', "%$search%");
        }
        
        return $query->orderBy('created_at', 'DESC')->get();
    }
    
    public function create($data)
    {
        return $this->model->create($data);
    }
    
    public function update($id, $data)
    {
        return $this->model->update($id, $data);
    }
    
    public function delete($id)
    {
        return $this->model->delete($id);
    }
    
    public function getTotalCount()
    {
        return $this->model->count();
    }
    
    public function getActiveCount()
    {
        return $this->model->where('status', 'active')->count();
    }
    
    public function getRecentUsers($limit = 10)
    {
        return $this->model->orderBy('created_at', 'DESC')->limit($limit)->get();
    }
}
