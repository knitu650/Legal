<?php

namespace App\Repositories;

use App\Models\DocumentTemplate;

class TemplateRepository
{
    protected $model;
    
    public function __construct()
    {
        $this->model = new DocumentTemplate();
    }
    
    public function findById($id)
    {
        return $this->model->find($id);
    }
    
    public function getAll($filters = [])
    {
        $query = $this->model->where('is_active', 1);
        
        if (isset($filters['category_id'])) {
            $query = $query->where('category_id', $filters['category_id']);
        }
        
        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query = $query->where('name', 'LIKE', "%$search%");
        }
        
        return $query->orderBy('downloads', 'DESC')->get();
    }
    
    public function getPopular($limit = 10)
    {
        return $this->model->where('is_active', 1)
                          ->orderBy('downloads', 'DESC')
                          ->limit($limit)
                          ->get();
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
}
