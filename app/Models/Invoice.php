<?php

namespace App\Models;

use App\Core\Model;

class Invoice extends Model
{
    protected $table = 'invoices';
    protected $fillable = [
        'user_id', 'transaction_id', 'invoice_number', 
        'amount', 'tax', 'total', 'status', 'due_date', 'paid_at'
    ];
    
    public function user()
    {
        $userModel = new User();
        return $userModel->find($this->user_id);
    }
    
    public function transaction()
    {
        $transactionModel = new Transaction();
        return $transactionModel->find($this->transaction_id);
    }
    
    public function isPaid()
    {
        return $this->status === 'paid';
    }
    
    public function isPending()
    {
        return $this->status === 'pending';
    }
    
    public function isOverdue()
    {
        return $this->status === 'pending' && strtotime($this->due_date) < time();
    }
    
    public function markAsPaid()
    {
        return $this->update($this->id, [
            'status' => 'paid',
            'paid_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    public static function generateInvoiceNumber()
    {
        $prefix = 'INV';
        $date = date('Ymd');
        $random = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        return $prefix . '-' . $date . '-' . $random;
    }
    
    public function getTotalAmount()
    {
        return $this->amount + $this->tax;
    }
}
