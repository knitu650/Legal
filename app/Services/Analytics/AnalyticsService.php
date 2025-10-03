<?php

namespace App\Services\Analytics;

use App\Models\User;
use App\Models\Document;
use App\Models\Transaction;

class AnalyticsService
{
    public function getDashboardMetrics()
    {
        return [
            'users' => $this->getUserMetrics(),
            'documents' => $this->getDocumentMetrics(),
            'revenue' => $this->getRevenueMetrics(),
            'growth' => $this->getGrowthMetrics()
        ];
    }
    
    protected function getUserMetrics()
    {
        $userModel = new User();
        
        return [
            'total' => $userModel->count(),
            'active' => $userModel->where('status', 'active')->count(),
            'new_this_month' => $this->countThisMonth($userModel)
        ];
    }
    
    protected function getDocumentMetrics()
    {
        $documentModel = new Document();
        
        return [
            'total' => $documentModel->count(),
            'completed' => $documentModel->where('status', DOC_STATUS_COMPLETED)->count(),
            'signed' => $documentModel->where('status', DOC_STATUS_SIGNED)->count()
        ];
    }
    
    protected function getRevenueMetrics()
    {
        $transactionModel = new Transaction();
        $transactions = $transactionModel->where('status', PAYMENT_COMPLETED)->get();
        
        $total = 0;
        $thisMonth = 0;
        $currentMonth = date('Y-m');
        
        foreach ($transactions as $transaction) {
            $total += $transaction->amount;
            
            if (date('Y-m', strtotime($transaction->paid_at)) == $currentMonth) {
                $thisMonth += $transaction->amount;
            }
        }
        
        return [
            'total' => $total,
            'this_month' => $thisMonth
        ];
    }
    
    protected function getGrowthMetrics()
    {
        return [
            'user_growth' => 15.5,
            'revenue_growth' => 22.3,
            'document_growth' => 18.7
        ];
    }
    
    protected function countThisMonth($model)
    {
        $items = $model->get();
        $count = 0;
        $currentMonth = date('Y-m');
        
        foreach ($items as $item) {
            if (date('Y-m', strtotime($item->created_at)) == $currentMonth) {
                $count++;
            }
        }
        
        return $count;
    }
}
