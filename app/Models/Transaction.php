<?php

namespace App\Models;

use App\Core\Model;

class Transaction extends Model
{
    protected $table = 'transactions';
    protected $fillable = [
        'user_id', 'type', 'amount', 'currency', 'status',
        'payment_method', 'payment_id', 'metadata', 'paid_at'
    ];
    
    public function user()
    {
        $userModel = new User();
        return $userModel->find($this->user_id);
    }
    
    public function isCompleted()
    {
        return $this->status === PAYMENT_COMPLETED;
    }
    
    public function isPending()
    {
        return $this->status === PAYMENT_PENDING;
    }
    
    public function getMetadata()
    {
        return json_decode($this->metadata, true) ?? [];
    }
}
