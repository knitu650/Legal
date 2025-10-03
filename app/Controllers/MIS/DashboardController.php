<?php

namespace App\Controllers\MIS;

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
        
        // Key Performance Indicators
        $kpis = [
            'total_users' => $userModel->count(),
            'active_users' => $userModel->where('status', 'active')->count(),
            'total_documents' => $documentModel->count(),
            'total_revenue' => $this->calculateTotalRevenue(),
            'active_subscriptions' => $subscriptionModel->where('status', SUBSCRIPTION_ACTIVE)->count(),
            'monthly_revenue' => $this->getMonthlyRevenue(),
            'user_growth_rate' => $this->getUserGrowthRate(),
            'document_completion_rate' => $this->getDocumentCompletionRate(),
        ];
        
        // Charts data
        $charts = [
            'revenue_trend' => $this->getRevenueTrend(12),
            'user_acquisition' => $this->getUserAcquisition(12),
            'document_stats' => $this->getDocumentStats(),
            'subscription_distribution' => $this->getSubscriptionDistribution(),
        ];
        
        $this->view('mis/dashboard/index', [
            'pageTitle' => 'MIS Dashboard',
            'kpis' => $kpis,
            'charts' => $charts
        ]);
    }
    
    protected function calculateTotalRevenue()
    {
        $transactionModel = new Transaction();
        $transactions = $transactionModel->where('status', PAYMENT_COMPLETED)->get();
        
        $total = 0;
        foreach ($transactions as $transaction) {
            $total += $transaction->amount;
        }
        
        return $total;
    }
    
    protected function getMonthlyRevenue()
    {
        $transactionModel = new Transaction();
        $transactions = $transactionModel->where('status', PAYMENT_COMPLETED)->get();
        
        $total = 0;
        $currentMonth = date('Y-m');
        
        foreach ($transactions as $transaction) {
            if (date('Y-m', strtotime($transaction->paid_at)) == $currentMonth) {
                $total += $transaction->amount;
            }
        }
        
        return $total;
    }
    
    protected function getUserGrowthRate()
    {
        $userModel = new User();
        $thisMonth = 0;
        $lastMonth = 0;
        
        $users = $userModel->get();
        $currentMonth = date('Y-m');
        $previousMonth = date('Y-m', strtotime('-1 month'));
        
        foreach ($users as $user) {
            $userMonth = date('Y-m', strtotime($user->created_at));
            if ($userMonth == $currentMonth) $thisMonth++;
            if ($userMonth == $previousMonth) $lastMonth++;
        }
        
        if ($lastMonth == 0) return 0;
        
        return round((($thisMonth - $lastMonth) / $lastMonth) * 100, 2);
    }
    
    protected function getDocumentCompletionRate()
    {
        $documentModel = new Document();
        $total = $documentModel->count();
        $completed = $documentModel->where('status', DOC_STATUS_COMPLETED)->count();
        
        if ($total == 0) return 0;
        
        return round(($completed / $total) * 100, 2);
    }
    
    protected function getRevenueTrend($months = 12)
    {
        $data = ['labels' => [], 'values' => []];
        $transactionModel = new Transaction();
        
        for ($i = $months - 1; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i months"));
            $data['labels'][] = date('M Y', strtotime("-$i months"));
            
            $transactions = $transactionModel->where('status', PAYMENT_COMPLETED)->get();
            $monthRevenue = 0;
            
            foreach ($transactions as $transaction) {
                if (date('Y-m', strtotime($transaction->paid_at)) == $month) {
                    $monthRevenue += $transaction->amount;
                }
            }
            
            $data['values'][] = $monthRevenue;
        }
        
        return $data;
    }
    
    protected function getUserAcquisition($months = 12)
    {
        $data = ['labels' => [], 'values' => []];
        $userModel = new User();
        
        for ($i = $months - 1; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i months"));
            $data['labels'][] = date('M Y', strtotime("-$i months"));
            
            $users = $userModel->get();
            $monthCount = 0;
            
            foreach ($users as $user) {
                if (date('Y-m', strtotime($user->created_at)) == $month) {
                    $monthCount++;
                }
            }
            
            $data['values'][] = $monthCount;
        }
        
        return $data;
    }
    
    protected function getDocumentStats()
    {
        $documentModel = new Document();
        
        return [
            'labels' => ['Draft', 'Pending', 'Completed', 'Signed', 'Archived'],
            'values' => [
                $documentModel->where('status', DOC_STATUS_DRAFT)->count(),
                $documentModel->where('status', DOC_STATUS_PENDING)->count(),
                $documentModel->where('status', DOC_STATUS_COMPLETED)->count(),
                $documentModel->where('status', DOC_STATUS_SIGNED)->count(),
                $documentModel->where('status', DOC_STATUS_ARCHIVED)->count(),
            ]
        ];
    }
    
    protected function getSubscriptionDistribution()
    {
        // Placeholder - implement based on subscription plans
        return [
            'labels' => ['Free', 'Basic', 'Premium', 'Enterprise'],
            'values' => [0, 0, 0, 0]
        ];
    }
}
