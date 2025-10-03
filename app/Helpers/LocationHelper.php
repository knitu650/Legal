<?php

namespace App\Helpers;

class LocationHelper
{
    public static function getStates()
    {
        return config('locations.states', []);
    }
    
    public static function getStateName($code)
    {
        $states = self::getStates();
        return $states[$code] ?? $code;
    }
    
    public static function getStampDuty($state)
    {
        $rates = config('locations.stamp_duty', []);
        return $rates[$state] ?? 5.0;
    }
    
    public static function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // km
        
        $latDiff = deg2rad($lat2 - $lat1);
        $lonDiff = deg2rad($lon2 - $lon1);
        
        $a = sin($latDiff / 2) * sin($latDiff / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($lonDiff / 2) * sin($lonDiff / 2);
        
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        
        return $earthRadius * $c;
    }
    
    public static function formatAddress($parts)
    {
        return implode(', ', array_filter($parts));
    }
}
