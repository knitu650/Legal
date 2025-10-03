<?php

namespace App\Controllers\User;

use App\Core\Controller;
use App\Models\Transaction;
use App\Models\Invoice;

class BillingController extends Controller
{
    public function index()
    {
        $user = $this->auth();
        $transactionModel = new Transaction();
        
        $transactions = $transactionModel->where('user_id', $user->id)
                                        ->orderBy('created_at', 'DESC')
                                        ->get();
        
        $this->view('user/billing/index', [
            'pageTitle' => 'Billing',
            'transactions' => $transactions
        ]);
    }
    
    public function invoices()
    {
        $user = $this->auth();
        $invoiceModel = new Invoice();
        
        $invoices = $invoiceModel->where('user_id', $user->id)
                                ->orderBy('created_at', 'DESC')
                                ->get();
        
        $this->view('user/billing/invoices', [
            'pageTitle' => 'Invoices',
            'invoices' => $invoices
        ]);
    }
    
    public function downloadInvoice($id)
    {
        $user = $this->auth();
        $invoiceModel = new Invoice();
        $invoice = $invoiceModel->find($id);
        
        if (!$invoice || $invoice->user_id != $user->id) {
            flash('error', 'Invoice not found.');
            $this->redirect('/user/billing/invoices');
            return;
        }
        
        // Generate and download PDF invoice
        flash('info', 'Invoice download feature coming soon!');
        $this->back();
    }
    
    public function paymentMethods()
    {
        $this->view('user/billing/payment-methods', [
            'pageTitle' => 'Payment Methods'
        ]);
    }
}
