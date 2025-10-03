<?php

namespace App\Services\Subscription;

use App\Models\Invoice;
use App\Models\Transaction;

class BillingService
{
    public function generateBill($userId, $subscriptionId, $amount)
    {
        $invoiceModel = new Invoice();
        
        $tax = $this->calculateTax($amount);
        
        $invoice = $invoiceModel->create([
            'user_id' => $userId,
            'invoice_number' => Invoice::generateInvoiceNumber(),
            'amount' => $amount,
            'tax' => $tax,
            'total' => $amount + $tax,
            'status' => 'pending',
            'due_date' => date('Y-m-d', strtotime('+7 days'))
        ]);
        
        return $invoice;
    }
    
    public function processBilling($userId, $planId)
    {
        $planModel = new \App\Models\SubscriptionPlan();
        $plan = $planModel->find($planId);
        
        if (!$plan) {
            return false;
        }
        
        // Generate invoice
        $invoice = $this->generateBill($userId, null, $plan->price);
        
        return $invoice;
    }
    
    protected function calculateTax($amount)
    {
        $taxRate = 18; // 18% GST in India
        return ($amount * $taxRate) / 100;
    }
    
    public function getUserBillingHistory($userId)
    {
        $invoiceModel = new Invoice();
        return $invoiceModel->where('user_id', $userId)
                           ->orderBy('created_at', 'DESC')
                           ->get();
    }
}
