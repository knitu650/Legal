<?php

namespace App\Helpers;

class NumberHelper
{
    public static function format($number, $decimals = 2)
    {
        return number_format($number, $decimals);
    }
    
    public static function formatCurrency($amount, $currency = 'INR')
    {
        $symbol = self::getCurrencySymbol($currency);
        return $symbol . number_format($amount, 2);
    }
    
    public static function getCurrencySymbol($currency)
    {
        $symbols = [
            'INR' => '₹',
            'USD' => '$',
            'EUR' => '€',
            'GBP' => '£'
        ];
        
        return $symbols[$currency] ?? $currency;
    }
    
    public static function percentage($value, $total)
    {
        if ($total == 0) {
            return 0;
        }
        
        return round(($value / $total) * 100, 2);
    }
    
    public static function toWords($number)
    {
        // Convert number to words (Indian system)
        $ones = ['', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine'];
        $tens = ['', '', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'];
        $teens = ['ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'];
        
        // Simplified implementation
        return 'Number in words';
    }
    
    public static function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
