<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\User;
use App\Models\Document;
use App\Models\Transaction;
use App\Models\Subscription;

class AnalyticsController extends Controller
{
    public function index()
    {
        $userModel = new User();
        $documentModel = new Document();
        $transactionModel = new Transaction();
        $subscriptionModel = new Subscription();
        
        // Overall metrics
        $metrics = [
            'total_users' => $userModel->count(),
            'total_documents' => $documentModel->count(),
            'total_revenue' => $this->calculateTotalRevenue(),
            'active_subscriptions' => $subscriptionModel->where('status', SUBSCRIPTION_ACTIVE)->count(),
        ];
        
        // Growth metrics
        $growth = [
            'users_this_month' => $this->getUsersThisMonth(),
            'documents_this_month' => $this->getDocumentsThisMonth(),
            'revenue_this_month' => $this->getRevenueThisMonth(),
        ];
        
        // Chart data
        $charts = [
            'user_growth' => $this->getUserGrowthData(),
            'revenue_trend' => $this->getRevenueTrendData(),
            'document_status' => $this->getDocumentStatusData(),
        ];
        
        $this->view('admin/analytics/index', [
            'pageTitle' => 'Analytics Dashboard',
            'metrics' => $metrics,
            'growth' => $growth,
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
    
    protected function getUsersThisMonth()
    {
        $userModel = new User();
        $users = $userModel->get();
        
        $count = 0;
        $currentMonth = date('Y-m');
        
        foreach ($users as $user) {
            if (date('Y-m', strtotime($user->created_at)) == $currentMonth) {
                $count++;
            }
        }
        
        return $count;
    }
    
    protected function getDocumentsThisMonth()
    {
        $documentModel = new Document();
        $documents = $documentModel->get();
        
        $count = 0;
        $currentMonth = date('Y-m');
        
        foreach ($documents as $doc) {
            if (date('Y-m', strtotime($doc->created_at)) == $currentMonth) {
                $count++;
            }
        }
        
        return $count;
    }
    
    protected function getRevenueThisMonth()
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
    
    protected function getUserGrowthData()
    {
        $data = ['labels' => [], 'values' => []];
        
        for ($i = 11; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i months"));
            $data['labels'][] = date('M Y', strtotime("-$i months"));
            $data['values'][] = 0; // Calculate actual count
        }
        
        return $data;
    }
    
    protected function getRevenueTrendData()
    {
        $data = ['labels' => [], 'values' => []];
        
        for ($i = 11; $i >= 0; $i--) {
            $data['labels'][] = date('M Y', strtotime("-$i months"));
            $data['values'][] = 0; // Calculate actual revenue
        }
        
        return $data;
    }
    
    protected function getDocumentStatusData()
    {
        $documentModel = new Document();
        
        return [
            'labels' => ['Draft', 'Completed', 'Signed', 'Archived'],
            'values' => [
                $documentModel->where('status', DOC_STATUS_DRAFT)->count(),
                $documentModel->where('status', DOC_STATUS_COMPLETED)->count(),
                $documentModel->where('status', DOC_STATUS_SIGNED)->count(),
                $documentModel->where('status', DOC_STATUS_ARCHIVED)->count(),
            ]
        ];
    }
}
