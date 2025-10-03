<?php

namespace App\Controllers\Api\V1;

use App\Core\Controller;
use App\Models\Location;

class LocationApiController extends Controller
{
    public function index()
    {
        $locationModel = new Location();
        
        $state = $this->request->query('state');
        $city = $this->request->query('city');
        
        $query = $locationModel->where('is_active', 1);
        
        if ($state) {
            $query = $query->where('state', $state);
        }
        
        if ($city) {
            $query = $query->where('city', $city);
        }
        
        $locations = $query->orderBy('state', 'ASC')->get();
        
        $this->json([
            'success' => true,
            'data' => $locations
        ]);
    }
    
    public function show($id)
    {
        $locationModel = new Location();
        $location = $locationModel->find($id);
        
        if (!$location) {
            $this->json(['error' => 'Location not found'], 404);
            return;
        }
        
        $this->json($location);
    }
}
