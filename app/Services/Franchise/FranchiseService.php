<?php

namespace App\Services\Franchise;

use App\Models\Franchise;
use App\Models\FranchiseApplication;

class FranchiseService
{
    public function applyForFranchise($userId, $data)
    {
        $applicationModel = new FranchiseApplication();
        
        $application = $applicationModel->create([
            'user_id' => $userId,
            'business_name' => $data['business_name'],
            'location_id' => $data['location_id'],
            'investment_capacity' => $data['investment_capacity'],
            'experience' => $data['experience'],
            'proposed_territory' => json_encode($data['proposed_territory'] ?? []),
            'status' => 'pending'
        ]);
        
        return $application;
    }
    
    public function getFranchisePerformance($franchiseId)
    {
        $franchiseModel = new Franchise();
        $franchise = $franchiseModel->find($franchiseId);
        
        if (!$franchise) {
            return null;
        }
        
        return [
            'total_customers' => 0,
            'total_documents' => 0,
            'total_revenue' => 0,
            'commission_earned' => 0,
            'rating' => 0
        ];
    }
    
    public function activateFranchise($franchiseId)
    {
        $franchiseModel = new Franchise();
        $franchise = $franchiseModel->find($franchiseId);
        
        if ($franchise) {
            $franchise->activate();
            return true;
        }
        
        return false;
    }
}
