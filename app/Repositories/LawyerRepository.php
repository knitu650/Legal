<?php

namespace App\Repositories;

use App\Models\Lawyer;

class LawyerRepository
{
    protected $model;
    
    public function __construct()
    {
        $this->model = new Lawyer();
    }
    
    public function findById($id)
    {
        return $this->model->find($id);
    }
    
    public function getVerifiedLawyers()
    {
        return $this->model->where('verification_status', 'verified')
                          ->orderBy('rating', 'DESC')
                          ->get();
    }
    
    public function getPendingVerification()
    {
        return $this->model->where('verification_status', 'pending')
                          ->orderBy('created_at', 'ASC')
                          ->get();
    }
    
    public function searchBySpecialization($specialization)
    {
        return $this->model->where('specialization', 'LIKE', "%$specialization%")
                          ->where('verification_status', 'verified')
                          ->get();
    }
    
    public function getTopRated($limit = 10)
    {
        return $this->model->where('verification_status', 'verified')
                          ->orderBy('rating', 'DESC')
                          ->limit($limit)
                          ->get();
    }
}
