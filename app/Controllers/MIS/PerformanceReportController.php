<?php

namespace App\Controllers\MIS;

use App\Core\Controller;

class PerformanceReportController extends Controller
{
    public function index()
    {
        $report = [
            'response_time' => $this->getAverageResponseTime(),
            'uptime' => $this->getUptime(),
            'error_rate' => $this->getErrorRate(),
            'concurrent_users' => $this->getConcurrentUsers(),
            'page_load_time' => $this->getPageLoadTime(),
        ];
        
        $this->view('mis/reports/performance', [
            'pageTitle' => 'Performance Report',
            'report' => $report
        ]);
    }
    
    protected function getAverageResponseTime()
    {
        return '250ms';
    }
    
    protected function getUptime()
    {
        return '99.9%';
    }
    
    protected function getErrorRate()
    {
        return '0.1%';
    }
    
    protected function getConcurrentUsers()
    {
        return 150;
    }
    
    protected function getPageLoadTime()
    {
        return '1.2s';
    }
}
