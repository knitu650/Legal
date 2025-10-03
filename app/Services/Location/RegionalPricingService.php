<?php

namespace App\Services\Location;

class RegionalPricingService
{
    protected $pricingMatrix = [
        'MH' => ['multiplier' => 1.0],
        'DL' => ['multiplier' => 1.1],
        'KA' => ['multiplier' => 0.95],
        'TN' => ['multiplier' => 0.9],
        'GJ' => ['multiplier' => 0.85],
    ];
    
    public function getPriceForLocation($basePrice, $state)
    {
        if (isset($this->pricingMatrix[$state])) {
            $multiplier = $this->pricingMatrix[$state]['multiplier'];
            return $basePrice * $multiplier;
        }
        
        return $basePrice;
    }
    
    public function applyRegionalDiscount($price, $state)
    {
        $discounts = [
            'RJ' => 5, // 5% discount
            'UP' => 10,
        ];
        
        if (isset($discounts[$state])) {
            $discount = ($price * $discounts[$state]) / 100;
            return $price - $discount;
        }
        
        return $price;
    }
}
