<?php

namespace App\Middleware;

use App\Core\Request;
use App\Core\Session;

class LocationMiddleware
{
    public function handle(Request $request)
    {
        // Detect user location if not already set
        if (!Session::has('user_location')) {
            $location = $this->detectLocation($request);
            Session::set('user_location', $location);
        }
        
        return true;
    }
    
    protected function detectLocation(Request $request)
    {
        $ip = $request->ip();
        
        // In production, use IP geolocation service
        // For now, returning default location
        return [
            'country' => 'India',
            'state' => 'Maharashtra',
            'city' => 'Mumbai',
            'ip' => $ip
        ];
    }
}
