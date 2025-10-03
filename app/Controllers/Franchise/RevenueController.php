<?php

namespace App\Controllers\Franchise;

use App\Core\Controller;
use App\Models\Transaction;

class RevenueController extends Controller
{
    public function index()
    {
        $user = $this->auth();
        
        $stats = [
            'total_revenue' => 0,
            'this_month' => 0,
            'last_month' => 0,
            'revenue_trend' => []
        ];
        
        $this->view('franchise/revenue/index', [
            'pageTitle' => 'Revenue',
            'stats' => $stats
        ]);
    }
}
