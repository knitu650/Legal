<?php

namespace App\Repositories;

use App\Models\Location;

class LocationRepository
{
    protected $model;
    
    public function __construct()
    {
        $this->model = new Location();
    }
    
    public function findById($id)
    {
        return $this->model->find($id);
    }
    
    public function getAll()
    {
        return $this->model->where('is_active', 1)
                          ->orderBy('state', 'ASC')
                          ->get();
    }
    
    public function getByState($state)
    {
        return $this->model->where('state', $state)
                          ->where('is_active', 1)
                          ->get();
    }
    
    public function getByCity($city)
    {
        return $this->model->where('city', $city)
                          ->where('is_active', 1)
                          ->get();
    }
    
    public function findByPincode($pincode)
    {
        return $this->model->where('pincode', $pincode)->first();
    }
}
