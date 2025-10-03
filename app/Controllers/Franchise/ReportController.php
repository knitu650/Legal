<?php

namespace App\Controllers\Franchise;

use App\Core\Controller;

class ReportController extends Controller
{
    public function index()
    {
        $this->view('franchise/reports/index', [
            'pageTitle' => 'Reports'
        ]);
    }
    
    public function daily()
    {
        $this->view('franchise/reports/daily', [
            'pageTitle' => 'Daily Report'
        ]);
    }
    
    public function monthly()
    {
        $this->view('franchise/reports/monthly', [
            'pageTitle' => 'Monthly Report'
        ]);
    }
}
