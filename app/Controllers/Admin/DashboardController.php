<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\User;
use App\Models\Document;
use App\Models\Transaction;
use App\Models\Subscription;

class DashboardController extends Controller
{
    public function index()
    {
        $userModel = new User();
        $documentModel = new Document();
        $transactionModel = new Transaction();
        $subscriptionModel = new Subscription();
        
        // Overall statistics
        $stats = [
            'total_users' => $userModel->count(),
            'total_documents' => $documentModel->count(),
            'total_revenue' => $transactionModel->where('status', PAYMENT_COMPLETED)
                                               ->get(),
            'active_subscriptions' => $subscriptionModel->where('status', SUBSCRIPTION_ACTIVE)
                                                        ->count(),
        ];
        
        // Calculate total revenue
        $revenue = 0;
        foreach ($stats['total_revenue'] as $transaction) {
            $revenue += $transaction->amount;
        }
        $stats['total_revenue'] = $revenue;
        
        // Recent users
        $recentUsers = $userModel->orderBy('created_at', 'DESC')
                                ->limit(10)
                                ->get();
        
        // Recent documents
        $recentDocuments = $documentModel->orderBy('created_at', 'DESC')
                                        ->limit(10)
                                        ->get();
        
        // Recent transactions
        $recentTransactions = $transactionModel->orderBy('created_at', 'DESC')
                                              ->limit(10)
                                              ->get();
        
        // Monthly revenue chart data
        $monthlyRevenue = $this->getMonthlyRevenue();
        
        $this->view('admin/dashboard/index', [
            'pageTitle' => 'Admin Dashboard',
            'stats' => $stats,
            'recentUsers' => $recentUsers,
            'recentDocuments' => $recentDocuments,
            'recentTransactions' => $recentTransactions,
            'monthlyRevenue' => $monthlyRevenue,
        ]);
    }
    
    protected function getMonthlyRevenue()
    {
        $transactionModel = new Transaction();
        $months = [];
        $revenue = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i months"));
            $months[] = date('M Y', strtotime("-$i months"));
            
            $monthTransactions = $transactionModel
                ->where('status', PAYMENT_COMPLETED)
                ->get();
            
            $monthRevenue = 0;
            foreach ($monthTransactions as $transaction) {
                if (date('Y-m', strtotime($transaction->created_at)) == $month) {
                    $monthRevenue += $transaction->amount;
                }
            }
            
            $revenue[] = $monthRevenue;
        }
        
        return [
            'labels' => $months,
            'data' => $revenue,
        ];
    }
}
