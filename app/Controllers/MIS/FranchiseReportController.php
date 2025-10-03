<?php

namespace App\Controllers\MIS;

use App\Core\Controller;
use App\Models\Franchise;

class FranchiseReportController extends Controller
{
    public function index()
    {
        $franchiseModel = new Franchise();
        
        $report = [
            'total_franchises' => $franchiseModel->count(),
            'active_franchises' => $franchiseModel->where('status', FRANCHISE_ACTIVE)->count(),
            'pending_franchises' => $franchiseModel->where('status', FRANCHISE_PENDING)->count(),
            'suspended_franchises' => $franchiseModel->where('status', FRANCHISE_SUSPENDED)->count(),
            'by_location' => $this->getFranchisesByLocation(),
            'revenue_by_franchise' => $this->getRevenueByFranchise(),
            'top_performers' => $this->getTopPerformers(),
        ];
        
        $this->view('mis/reports/franchises', [
            'pageTitle' => 'Franchise Report',
            'report' => $report
        ]);
    }
    
    protected function getFranchisesByLocation()
    {
        $franchiseModel = new Franchise();
        $franchises = $franchiseModel->get();
        
        $byLocation = [];
        foreach ($franchises as $franchise) {
            if ($franchise->location_id) {
                if (!isset($byLocation[$franchise->location_id])) {
                    $byLocation[$franchise->location_id] = 0;
                }
                $byLocation[$franchise->location_id]++;
            }
        }
        
        return $byLocation;
    }
    
    protected function getRevenueByFranchise()
    {
        // Placeholder - calculate actual revenue per franchise
        return [];
    }
    
    protected function getTopPerformers()
    {
        $franchiseModel = new Franchise();
        return $franchiseModel->where('status', FRANCHISE_ACTIVE)
                             ->orderBy('created_at', 'DESC')
                             ->limit(10)
                             ->get();
    }
}
