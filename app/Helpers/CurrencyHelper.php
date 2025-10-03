<?php

namespace App\Helpers;

class CurrencyHelper
{
    protected static $rates = [
        'INR' => 1,
        'USD' => 0.012,
        'EUR' => 0.011,
        'GBP' => 0.0095
    ];
    
    public static function convert($amount, $from, $to)
    {
        $amountInBase = $amount / self::$rates[$from];
        return $amountInBase * self::$rates[$to];
    }
    
    public static function format($amount, $currency = 'INR')
    {
        return NumberHelper::formatCurrency($amount, $currency);
    }
    
    public static function getSymbol($currency)
    {
        return NumberHelper::getCurrencySymbol($currency);
    }
    
    public static function toWords($amount, $currency = 'INR')
    {
        $rupees = floor($amount);
        $paise = round(($amount - $rupees) * 100);
        
        $words = NumberHelper::toWords($rupees);
        
        if ($paise > 0) {
            $words .= ' and ' . NumberHelper::toWords($paise) . ' paise';
        }
        
        return $words . ' only';
    }
}
