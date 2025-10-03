<?php

namespace App\Services\Location;

use App\Models\Location;

class LocationService
{
    public function findNearbyLocations($latitude, $longitude, $radius = 10)
    {
        // Haversine formula to find nearby locations
        $locationModel = new Location();
        $locations = $locationModel->where('is_active', 1)->get();
        
        $nearby = [];
        
        foreach ($locations as $location) {
            $distance = $this->calculateDistance(
                $latitude, 
                $longitude, 
                $location->latitude, 
                $location->longitude
            );
            
            if ($distance <= $radius) {
                $location->distance = $distance;
                $nearby[] = $location;
            }
        }
        
        // Sort by distance
        usort($nearby, function($a, $b) {
            return $a->distance <=> $b->distance;
        });
        
        return $nearby;
    }
    
    protected function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // kilometers
        
        $latDiff = deg2rad($lat2 - $lat1);
        $lonDiff = deg2rad($lon2 - $lon1);
        
        $a = sin($latDiff / 2) * sin($latDiff / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($lonDiff / 2) * sin($lonDiff / 2);
        
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        
        return $earthRadius * $c;
    }
    
    public function getLocationsByState($state)
    {
        return Location::findByState($state);
    }
    
    public function getLocationsByCity($city)
    {
        return Location::findByCity($city);
    }
}
