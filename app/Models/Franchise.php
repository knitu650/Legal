<?php

namespace App\Models;

use App\Core\Model;

class Franchise extends Model
{
    protected $table = 'franchises';
    protected $fillable = [
        'user_id', 'location_id', 'business_name', 'status',
        'commission_rate', 'territory', 'documents', 'approved_at'
    ];
    
    public function user()
    {
        $userModel = new User();
        return $userModel->find($this->user_id);
    }
    
    public function location()
    {
        $locationModel = new Location();
        return $locationModel->find($this->location_id);
    }
    
    public function isPending()
    {
        return $this->status === FRANCHISE_PENDING;
    }
    
    public function isApproved()
    {
        return $this->status === FRANCHISE_APPROVED;
    }
    
    public function isActive()
    {
        return $this->status === FRANCHISE_ACTIVE;
    }
    
    public function isSuspended()
    {
        return $this->status === FRANCHISE_SUSPENDED;
    }
    
    public function approve()
    {
        return $this->update($this->id, [
            'status' => FRANCHISE_APPROVED,
            'approved_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    public function activate()
    {
        return $this->update($this->id, [
            'status' => FRANCHISE_ACTIVE
        ]);
    }
    
    public function suspend()
    {
        return $this->update($this->id, [
            'status' => FRANCHISE_SUSPENDED
        ]);
    }
    
    public function getTerritory()
    {
        return json_decode($this->territory, true) ?? [];
    }
    
    public function getDocuments()
    {
        return json_decode($this->documents, true) ?? [];
    }
    
    public function calculateCommission($amount)
    {
        return ($amount * $this->commission_rate) / 100;
    }
}
