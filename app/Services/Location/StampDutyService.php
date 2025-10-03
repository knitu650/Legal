<?php

namespace App\Services\Location;

class StampDutyService
{
    protected $stampDutyRates;
    
    public function __construct()
    {
        $this->stampDutyRates = config('locations.stamp_duty', []);
    }
    
    public function calculateStampDuty($amount, $state, $documentType = 'general')
    {
        $rate = $this->getRate($state, $documentType);
        
        $stampDuty = ($amount * $rate) / 100;
        
        return [
            'amount' => $amount,
            'rate' => $rate,
            'stamp_duty' => $stampDuty,
            'total' => $amount + $stampDuty
        ];
    }
    
    protected function getRate($state, $documentType)
    {
        // Get state-specific rate
        if (isset($this->stampDutyRates[$state])) {
            return $this->stampDutyRates[$state];
        }
        
        // Default rate
        return 5.0;
    }
    
    public function getStampDutyByState()
    {
        return $this->stampDutyRates;
    }
}
