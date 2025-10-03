<?php

namespace App\Repositories;

use App\Models\Subscription;

class SubscriptionRepository
{
    protected $model;
    
    public function __construct()
    {
        $this->model = new Subscription();
    }
    
    public function findById($id)
    {
        return $this->model->find($id);
    }
    
    public function getUserSubscriptions($userId)
    {
        return $this->model->where('user_id', $userId)
                          ->orderBy('created_at', 'DESC')
                          ->get();
    }
    
    public function getActiveSubscription($userId)
    {
        return $this->model->where('user_id', $userId)
                          ->where('status', SUBSCRIPTION_ACTIVE)
                          ->first();
    }
    
    public function getExpiringSubscriptions($days = 7)
    {
        $expiryDate = date('Y-m-d', strtotime("+$days days"));
        
        return $this->model->where('status', SUBSCRIPTION_ACTIVE)
                          ->where('ends_at', '<=', $expiryDate)
                          ->get();
    }
    
    public function getActiveCount()
    {
        return $this->model->where('status', SUBSCRIPTION_ACTIVE)->count();
    }
}
