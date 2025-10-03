<?php

namespace App\Models;

use App\Core\Model;

class FranchiseApplication extends Model
{
    protected $table = 'franchise_applications';
    protected $fillable = [
        'user_id', 'business_name', 'location_id', 'investment_capacity',
        'experience', 'proposed_territory', 'documents', 'status',
        'reviewed_by', 'reviewed_at', 'rejection_reason'
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
    
    public function reviewer()
    {
        if ($this->reviewed_by) {
            $userModel = new User();
            return $userModel->find($this->reviewed_by);
        }
        return null;
    }
    
    public function isPending()
    {
        return $this->status === 'pending';
    }
    
    public function isApproved()
    {
        return $this->status === 'approved';
    }
    
    public function isRejected()
    {
        return $this->status === 'rejected';
    }
    
    public function approve($reviewerId)
    {
        return $this->update($this->id, [
            'status' => 'approved',
            'reviewed_by' => $reviewerId,
            'reviewed_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    public function reject($reviewerId, $reason)
    {
        return $this->update($this->id, [
            'status' => 'rejected',
            'reviewed_by' => $reviewerId,
            'reviewed_at' => date('Y-m-d H:i:s'),
            'rejection_reason' => $reason
        ]);
    }
    
    public function getDocuments()
    {
        return json_decode($this->documents, true) ?? [];
    }
    
    public function getProposedTerritory()
    {
        return json_decode($this->proposed_territory, true) ?? [];
    }
}
