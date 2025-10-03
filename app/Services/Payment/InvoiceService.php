<?php

namespace App\Services\Payment;

use App\Models\Invoice;
use App\Models\Transaction;

class InvoiceService
{
    public function createInvoice($userId, $transactionId, $amount, $tax = 0)
    {
        $invoiceModel = new Invoice();
        
        $invoice = $invoiceModel->create([
            'user_id' => $userId,
            'transaction_id' => $transactionId,
            'invoice_number' => Invoice::generateInvoiceNumber(),
            'amount' => $amount,
            'tax' => $tax,
            'total' => $amount + $tax,
            'status' => 'pending',
            'due_date' => date('Y-m-d', strtotime('+7 days'))
        ]);
        
        return $invoice;
    }
    
    public function markAsPaid($invoiceId, $transactionId = null)
    {
        $invoiceModel = new Invoice();
        
        $invoiceModel->update($invoiceId, [
            'status' => 'paid',
            'paid_at' => date('Y-m-d H:i:s'),
            'transaction_id' => $transactionId
        ]);
        
        return $invoiceModel->find($invoiceId);
    }
    
    public function generatePDF($invoiceId)
    {
        $invoiceModel = new Invoice();
        $invoice = $invoiceModel->find($invoiceId);
        
        if (!$invoice) {
            return null;
        }
        
        // Generate PDF invoice
        $html = $this->generateInvoiceHTML($invoice);
        
        // In production, use PDF library
        return $html;
    }
    
    protected function generateInvoiceHTML($invoice)
    {
        $html = "
        <html>
        <head><title>Invoice #{$invoice->invoice_number}</title></head>
        <body>
            <h1>Invoice #{$invoice->invoice_number}</h1>
            <p>Amount: " . currency($invoice->amount) . "</p>
            <p>Tax: " . currency($invoice->tax) . "</p>
            <p>Total: " . currency($invoice->total) . "</p>
            <p>Status: {$invoice->status}</p>
        </body>
        </html>
        ";
        
        return $html;
    }
}
