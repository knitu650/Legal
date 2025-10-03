<?php

namespace App\Controllers\MIS;

use App\Core\Controller;
use App\Models\Transaction;

class RevenueReportController extends Controller
{
    public function index()
    {
        $startDate = $this->request->query('start_date', date('Y-m-01'));
        $endDate = $this->request->query('end_date', date('Y-m-d'));
        $groupBy = $this->request->query('group_by', 'day');
        
        $transactionModel = new Transaction();
        $transactions = $transactionModel->where('status', PAYMENT_COMPLETED)->get();
        
        $report = [
            'total_revenue' => 0,
            'total_transactions' => 0,
            'average_transaction' => 0,
            'by_period' => [],
            'by_type' => [],
            'by_payment_method' => [],
        ];
        
        foreach ($transactions as $transaction) {
            if ($transaction->paid_at >= $startDate && $transaction->paid_at <= $endDate) {
                $report['total_revenue'] += $transaction->amount;
                $report['total_transactions']++;
                
                // Group by period
                $periodKey = $this->getPeriodKey($transaction->paid_at, $groupBy);
                if (!isset($report['by_period'][$periodKey])) {
                    $report['by_period'][$periodKey] = 0;
                }
                $report['by_period'][$periodKey] += $transaction->amount;
                
                // Group by type
                if (!isset($report['by_type'][$transaction->type])) {
                    $report['by_type'][$transaction->type] = 0;
                }
                $report['by_type'][$transaction->type] += $transaction->amount;
                
                // Group by payment method
                if ($transaction->payment_method) {
                    if (!isset($report['by_payment_method'][$transaction->payment_method])) {
                        $report['by_payment_method'][$transaction->payment_method] = 0;
                    }
                    $report['by_payment_method'][$transaction->payment_method] += $transaction->amount;
                }
            }
        }
        
        if ($report['total_transactions'] > 0) {
            $report['average_transaction'] = $report['total_revenue'] / $report['total_transactions'];
        }
        
        $this->view('mis/reports/revenue', [
            'pageTitle' => 'Revenue Report',
            'report' => $report,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'groupBy' => $groupBy
        ]);
    }
    
    protected function getPeriodKey($date, $groupBy)
    {
        switch ($groupBy) {
            case 'hour':
                return date('Y-m-d H:00', strtotime($date));
            case 'day':
                return date('Y-m-d', strtotime($date));
            case 'week':
                return date('Y-W', strtotime($date));
            case 'month':
                return date('Y-m', strtotime($date));
            case 'year':
                return date('Y', strtotime($date));
            default:
                return date('Y-m-d', strtotime($date));
        }
    }
}
