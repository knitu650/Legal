<?php

namespace App\Controllers\MIS;

use App\Core\Controller;
use App\Models\Transaction;

class FinancialReportController extends Controller
{
    public function index()
    {
        $transactionModel = new Transaction();
        
        $report = [
            'total_revenue' => $this->getTotalRevenue(),
            'net_profit' => $this->getNetProfit(),
            'expenses' => $this->getExpenses(),
            'profit_margin' => $this->getProfitMargin(),
            'cash_flow' => $this->getCashFlow(),
            'revenue_by_quarter' => $this->getRevenueByQuarter(),
        ];
        
        $this->view('mis/reports/financial', [
            'pageTitle' => 'Financial Report',
            'report' => $report
        ]);
    }
    
    protected function getTotalRevenue()
    {
        $transactionModel = new Transaction();
        $transactions = $transactionModel->where('status', PAYMENT_COMPLETED)->get();
        
        $total = 0;
        foreach ($transactions as $transaction) {
            $total += $transaction->amount;
        }
        
        return $total;
    }
    
    protected function getNetProfit()
    {
        return $this->getTotalRevenue() - $this->getExpenses();
    }
    
    protected function getExpenses()
    {
        // Placeholder - calculate actual expenses
        return 0;
    }
    
    protected function getProfitMargin()
    {
        $revenue = $this->getTotalRevenue();
        if ($revenue == 0) return 0;
        
        $profit = $this->getNetProfit();
        return round(($profit / $revenue) * 100, 2);
    }
    
    protected function getCashFlow()
    {
        return $this->getTotalRevenue(); // Simplified
    }
    
    protected function getRevenueByQuarter()
    {
        $data = [];
        $currentYear = date('Y');
        
        for ($q = 1; $q <= 4; $q++) {
            $data["Q$q $currentYear"] = 0;
        }
        
        return $data;
    }
}
