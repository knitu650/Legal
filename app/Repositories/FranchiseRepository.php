<?php

namespace App\Repositories;

use App\Models\Franchise;

class FranchiseRepository
{
    protected $model;
    
    public function __construct()
    {
        $this->model = new Franchise();
    }
    
    public function findById($id)
    {
        return $this->model->find($id);
    }
    
    public function getActiveFranchises()
    {
        return $this->model->where('status', FRANCHISE_ACTIVE)->get();
    }
    
    public function getPendingApprovals()
    {
        return $this->model->where('status', FRANCHISE_PENDING)->get();
    }
    
    public function getByLocation($locationId)
    {
        return $this->model->where('location_id', $locationId)->get();
    }
    
    public function getTotalCount()
    {
        return $this->model->count();
    }
}
