<?php

namespace App\Jobs;

use App\Services\Payment\PaymentService;

class ProcessPaymentJob
{
    protected $transactionId;
    
    public function __construct($transactionId)
    {
        $this->transactionId = $transactionId;
    }
    
    public function handle()
    {
        $paymentService = new PaymentService();
        
        // Process payment
        // Update transaction status
        // Send notifications
        
        return true;
    }
    
    public function dispatch()
    {
        return $this->handle();
    }
}
