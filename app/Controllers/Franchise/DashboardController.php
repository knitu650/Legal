<?php

namespace App\Controllers\Franchise;

use App\Core\Controller;
use App\Models\Franchise;

class DashboardController extends Controller
{
    public function index()
    {
        $user = $this->auth();
        $franchiseModel = new Franchise();
        
        $franchise = $franchiseModel->where('user_id', $user->id)->first();
        
        if (!$franchise) {
            flash('error', 'Franchise not found.');
            $this->redirect('/');
            return;
        }
        
        $stats = [
            'total_customers' => 0,
            'documents_this_month' => 0,
            'revenue_this_month' => 0,
            'commission_earned' => 0,
        ];
        
        $this->view('franchise/dashboard/index', [
            'pageTitle' => 'Franchise Dashboard',
            'franchise' => $franchise,
            'stats' => $stats
        ]);
    }
}
