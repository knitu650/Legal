<?php

namespace App\Services\Payment;

use App\Models\Transaction;

class PaymentService
{
    protected $gateway;
    
    public function __construct()
    {
        $defaultGateway = config('payment.default', 'razorpay');
        
        switch ($defaultGateway) {
            case 'razorpay':
                $this->gateway = new RazorpayService();
                break;
            case 'stripe':
                $this->gateway = new StripeService();
                break;
            case 'paytm':
                $this->gateway = new PaytmService();
                break;
            default:
                $this->gateway = new RazorpayService();
        }
    }
    
    public function createOrder($transactionId, $amount)
    {
        return $this->gateway->createOrder($transactionId, $amount);
    }
    
    public function verifyPayment($paymentId, $orderId, $signature)
    {
        return $this->gateway->verifyPayment($paymentId, $orderId, $signature);
    }
    
    public function processRefund($transactionId, $amount = null)
    {
        $transactionModel = new Transaction();
        $transaction = $transactionModel->find($transactionId);
        
        if (!$transaction || $transaction->status !== PAYMENT_COMPLETED) {
            return ['success' => false, 'message' => 'Transaction not eligible for refund'];
        }
        
        $refundAmount = $amount ?? $transaction->amount;
        
        $result = $this->gateway->refund($transaction->payment_id, $refundAmount);
        
        if ($result['success']) {
            $transactionModel->update($transactionId, [
                'status' => PAYMENT_REFUNDED
            ]);
        }
        
        return $result;
    }
    
    public function handleWebhook($payload, $signature)
    {
        return $this->gateway->handleWebhook($payload, $signature);
    }
}
