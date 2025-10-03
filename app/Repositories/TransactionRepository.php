<?php

namespace App\Repositories;

use App\Models\Transaction;

class TransactionRepository
{
    protected $model;
    
    public function __construct()
    {
        $this->model = new Transaction();
    }
    
    public function findById($id)
    {
        return $this->model->find($id);
    }
    
    public function getUserTransactions($userId, $filters = [])
    {
        $query = $this->model->where('user_id', $userId);
        
        if (isset($filters['status'])) {
            $query = $query->where('status', $filters['status']);
        }
        
        if (isset($filters['type'])) {
            $query = $query->where('type', $filters['type']);
        }
        
        return $query->orderBy('created_at', 'DESC')->get();
    }
    
    public function getCompletedTransactions($startDate = null, $endDate = null)
    {
        $query = $this->model->where('status', PAYMENT_COMPLETED);
        
        if ($startDate) {
            $query = $query->where('paid_at', '>=', $startDate);
        }
        
        if ($endDate) {
            $query = $query->where('paid_at', '<=', $endDate);
        }
        
        return $query->get();
    }
    
    public function getTotalRevenue()
    {
        $transactions = $this->model->where('status', PAYMENT_COMPLETED)->get();
        
        $total = 0;
        foreach ($transactions as $transaction) {
            $total += $transaction->amount;
        }
        
        return $total;
    }
}
