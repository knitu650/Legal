<?php

namespace App\Controllers\MIS;

use App\Core\Controller;
use App\Models\Location;

class LocationReportController extends Controller
{
    public function index()
    {
        $locationModel = new Location();
        
        $report = [
            'total_locations' => $locationModel->count(),
            'active_locations' => $locationModel->where('is_active', 1)->count(),
            'by_state' => $this->getLocationsByState(),
            'users_by_location' => $this->getUsersByLocation(),
            'revenue_by_location' => $this->getRevenueByLocation(),
        ];
        
        $this->view('mis/reports/locations', [
            'pageTitle' => 'Location Report',
            'report' => $report
        ]);
    }
    
    protected function getLocationsByState()
    {
        $locationModel = new Location();
        $locations = $locationModel->get();
        
        $byState = [];
        foreach ($locations as $location) {
            if (!isset($byState[$location->state])) {
                $byState[$location->state] = 0;
            }
            $byState[$location->state]++;
        }
        
        return $byState;
    }
    
    protected function getUsersByLocation()
    {
        // Placeholder
        return [];
    }
    
    protected function getRevenueByLocation()
    {
        // Placeholder
        return [];
    }
}
