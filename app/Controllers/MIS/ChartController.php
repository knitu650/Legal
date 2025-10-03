<?php

namespace App\Controllers\MIS;

use App\Core\Controller;

class ChartController extends Controller
{
    public function index()
    {
        $charts = [
            'revenue_chart' => $this->getRevenueChart(),
            'user_chart' => $this->getUserChart(),
            'document_chart' => $this->getDocumentChart(),
            'subscription_chart' => $this->getSubscriptionChart(),
        ];
        
        $this->view('mis/charts/index', [
            'pageTitle' => 'Charts & Visualizations',
            'charts' => $charts
        ]);
    }
    
    protected function getRevenueChart()
    {
        return [
            'type' => 'line',
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            'data' => [10000, 15000, 12000, 18000, 22000, 25000]
        ];
    }
    
    protected function getUserChart()
    {
        return [
            'type' => 'bar',
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            'data' => [50, 75, 60, 90, 110, 130]
        ];
    }
    
    protected function getDocumentChart()
    {
        return [
            'type' => 'pie',
            'labels' => ['Draft', 'Completed', 'Signed'],
            'data' => [30, 45, 25]
        ];
    }
    
    protected function getSubscriptionChart()
    {
        return [
            'type' => 'doughnut',
            'labels' => ['Free', 'Basic', 'Premium'],
            'data' => [40, 35, 25]
        ];
    }
}
