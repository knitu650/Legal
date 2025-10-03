<?php

namespace App\Repositories;

use App\Models\Document;

class DocumentRepository
{
    protected $model;
    
    public function __construct()
    {
        $this->model = new Document();
    }
    
    public function findById($id)
    {
        return $this->model->find($id);
    }
    
    public function getUserDocuments($userId, $filters = [])
    {
        $query = $this->model->where('user_id', $userId);
        
        if (isset($filters['status'])) {
            $query = $query->where('status', $filters['status']);
        }
        
        if (isset($filters['category_id'])) {
            $query = $query->where('category_id', $filters['category_id']);
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
    
    public function getCountByStatus($status)
    {
        return $this->model->where('status', $status)->count();
    }
    
    public function getRecentDocuments($limit = 10)
    {
        return $this->model->orderBy('created_at', 'DESC')->limit($limit)->get();
    }
    
    public function search($query, $filters = [])
    {
        $search = $this->model->where('title', 'LIKE', "%$query%");
        
        if (isset($filters['status'])) {
            $search = $search->where('status', $filters['status']);
        }
        
        return $search->get();
    }
}
