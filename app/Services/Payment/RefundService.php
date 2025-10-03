<?php

namespace App\Services\Payment;

use App\Models\Transaction;

class RefundService
{
    protected $paymentService;
    
    public function __construct()
    {
        $this->paymentService = new PaymentService();
    }
    
    public function processRefund($transactionId, $amount = null, $reason = '')
    {
        $transactionModel = new Transaction();
        $transaction = $transactionModel->find($transactionId);
        
        if (!$transaction) {
            return [
                'success' => false,
                'message' => 'Transaction not found'
            ];
        }
        
        if ($transaction->status !== PAYMENT_COMPLETED) {
            return [
                'success' => false,
                'message' => 'Transaction is not eligible for refund'
            ];
        }
        
        $refundAmount = $amount ?? $transaction->amount;
        
        if ($refundAmount > $transaction->amount) {
            return [
                'success' => false,
                'message' => 'Refund amount exceeds transaction amount'
            ];
        }
        
        // Process refund via payment gateway
        $result = $this->paymentService->processRefund($transactionId, $refundAmount);
        
        if ($result['success']) {
            // Update transaction
            $transactionModel->update($transactionId, [
                'status' => PAYMENT_REFUNDED,
                'metadata' => json_encode([
                    'refund_amount' => $refundAmount,
                    'refund_reason' => $reason,
                    'refunded_at' => date('Y-m-d H:i:s')
                ])
            ]);
        }
        
        return $result;
    }
    
    public function canRefund($transactionId)
    {
        $transactionModel = new Transaction();
        $transaction = $transactionModel->find($transactionId);
        
        if (!$transaction) {
            return false;
        }
        
        // Check if within refund window (e.g., 30 days)
        $refundWindow = 30 * 24 * 60 * 60; // 30 days in seconds
        $transactionAge = time() - strtotime($transaction->paid_at);
        
        return $transaction->status === PAYMENT_COMPLETED 
            && $transactionAge <= $refundWindow;
    }
}
