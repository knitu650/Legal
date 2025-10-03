<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Document;

class ReportController extends Controller
{
    public function index()
    {
        $this->view('admin/reports/index', [
            'pageTitle' => 'Reports'
        ]);
    }
    
    public function revenue()
    {
        $transactionModel = new Transaction();
        
        $startDate = $this->request->query('start_date', date('Y-m-01'));
        $endDate = $this->request->query('end_date', date('Y-m-d'));
        
        $transactions = $transactionModel->where('status', PAYMENT_COMPLETED)
                                        ->get();
        
        $revenue = [
            'total' => 0,
            'by_date' => [],
            'by_type' => []
        ];
        
        foreach ($transactions as $transaction) {
            if ($transaction->paid_at >= $startDate && $transaction->paid_at <= $endDate) {
                $revenue['total'] += $transaction->amount;
                
                $date = date('Y-m-d', strtotime($transaction->paid_at));
                if (!isset($revenue['by_date'][$date])) {
                    $revenue['by_date'][$date] = 0;
                }
                $revenue['by_date'][$date] += $transaction->amount;
                
                if (!isset($revenue['by_type'][$transaction->type])) {
                    $revenue['by_type'][$transaction->type] = 0;
                }
                $revenue['by_type'][$transaction->type] += $transaction->amount;
            }
        }
        
        $this->view('admin/reports/revenue', [
            'pageTitle' => 'Revenue Report',
            'revenue' => $revenue,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
    }
    
    public function users()
    {
        $userModel = new User();
        
        $stats = [
            'total' => $userModel->count(),
            'active' => $userModel->where('status', 'active')->count(),
            'inactive' => $userModel->where('status', 'inactive')->count(),
            'by_role' => []
        ];
        
        $this->view('admin/reports/users', [
            'pageTitle' => 'User Report',
            'stats' => $stats
        ]);
    }
    
    public function documents()
    {
        $documentModel = new Document();
        
        $stats = [
            'total' => $documentModel->count(),
            'draft' => $documentModel->where('status', DOC_STATUS_DRAFT)->count(),
            'completed' => $documentModel->where('status', DOC_STATUS_COMPLETED)->count(),
            'signed' => $documentModel->where('status', DOC_STATUS_SIGNED)->count(),
        ];
        
        $this->view('admin/reports/documents', [
            'pageTitle' => 'Document Report',
            'stats' => $stats
        ]);
    }
    
    public function custom()
    {
        $this->view('admin/reports/custom', [
            'pageTitle' => 'Custom Report'
        ]);
    }
}
