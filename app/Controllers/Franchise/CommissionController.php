<?php

namespace App\Controllers\Franchise;

use App\Core\Controller;
use App\Models\Franchise;

class CommissionController extends Controller
{
    public function index()
    {
        $user = $this->auth();
        $franchiseModel = new Franchise();
        
        $franchise = $franchiseModel->where('user_id', $user->id)->first();
        
        $commissions = [
            'total_earned' => 0,
            'pending_payout' => 0,
            'paid_out' => 0,
            'commission_rate' => $franchise ? $franchise->commission_rate : 0,
        ];
        
        $this->view('franchise/revenue/commissions', [
            'pageTitle' => 'Commissions',
            'commissions' => $commissions
        ]);
    }
}
