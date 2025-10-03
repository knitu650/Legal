<?php

namespace App\Services\Franchise;

use App\Models\Franchise;

class TerritoryService
{
    public function assignTerritory($franchiseId, $territory)
    {
        $franchiseModel = new Franchise();
        
        $franchiseModel->update($franchiseId, [
            'territory' => json_encode($territory)
        ]);
        
        return true;
    }
    
    public function checkTerritoryOverlap($territory1, $territory2)
    {
        // Check if territories overlap
        // Simplified implementation
        
        return false;
    }
    
    public function getTerritoryDetails($franchiseId)
    {
        $franchiseModel = new Franchise();
        $franchise = $franchiseModel->find($franchiseId);
        
        if (!$franchise) {
            return null;
        }
        
        return $franchise->getTerritory();
    }
}
