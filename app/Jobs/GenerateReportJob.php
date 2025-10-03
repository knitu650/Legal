<?php

namespace App\Jobs;

use App\Services\Analytics\ReportService;

class GenerateReportJob
{
    protected $reportType;
    protected $params;
    
    public function __construct($reportType, $params = [])
    {
        $this->reportType = $reportType;
        $this->params = $params;
    }
    
    public function handle()
    {
        $reportService = new ReportService();
        $report = $reportService->generateReport($this->reportType, $this->params);
        
        // Save report
        // Send notification
        
        return $report;
    }
    
    public function dispatch()
    {
        return $this->handle();
    }
}
