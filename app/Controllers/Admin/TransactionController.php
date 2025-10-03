<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Transaction;
use App\Services\Payment\PaymentService;

class TransactionController extends Controller
{
    public function index()
    {
        $transactionModel = new Transaction();
        $status = $this->request->query('status');
        
        $query = $transactionModel;
        
        if ($status) {
            $query = $query->where('status', $status);
        }
        
        $transactions = $query->orderBy('created_at', 'DESC')->get();
        
        $this->view('admin/transactions/index', [
            'pageTitle' => 'Transaction Management',
            'transactions' => $transactions
        ]);
    }
    
    public function show($id)
    {
        $transactionModel = new Transaction();
        $transaction = $transactionModel->find($id);
        
        if (!$transaction) {
            flash('error', 'Transaction not found.');
            $this->redirect('/admin/transactions');
            return;
        }
        
        $this->view('admin/transactions/show', [
            'pageTitle' => 'Transaction Details',
            'transaction' => $transaction
        ]);
    }
    
    public function refund($id)
    {
        $transactionModel = new Transaction();
        $transaction = $transactionModel->find($id);
        
        if (!$transaction || $transaction->status !== PAYMENT_COMPLETED) {
            flash('error', 'Transaction cannot be refunded.');
            $this->redirect('/admin/transactions/' . $id);
            return;
        }
        
        $paymentService = new PaymentService();
        $result = $paymentService->processRefund($id);
        
        if ($result['success']) {
            flash('success', 'Refund processed successfully!');
        } else {
            flash('error', 'Refund failed: ' . $result['message']);
        }
        
        $this->redirect('/admin/transactions/' . $id);
    }
}
