<?php

namespace App\Listeners;

use App\Services\Payment\InvoiceService;

class ProcessPayment
{
    protected $invoiceService;
    
    public function __construct()
    {
        $this->invoiceService = new InvoiceService();
    }
    
    public function handle($event)
    {
        $transaction = $event->transaction;
        
        // Generate invoice
        $this->invoiceService->createInvoice(
            $transaction->user_id,
            $transaction->id,
            $transaction->amount
        );
        
        // Send receipt email
    }
}
