<?php

namespace App\Events;

class PaymentCompleted
{
    public $transaction;
    
    public function __construct($transaction)
    {
        $this->transaction = $transaction;
    }
    
    public function dispatch()
    {
        $listeners = [
            new \App\Listeners\ProcessPayment()
        ];
        
        foreach ($listeners as $listener) {
            $listener->handle($this);
        }
    }
}
