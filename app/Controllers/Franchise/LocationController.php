<?php

namespace App\Controllers\Franchise;

use App\Core\Controller;
use App\Models\Franchise;
use App\Models\Location;

class LocationController extends Controller
{
    public function index()
    {
        $user = $this->auth();
        $franchiseModel = new Franchise();
        
        $franchise = $franchiseModel->where('user_id', $user->id)->first();
        
        $location = null;
        if ($franchise && $franchise->location_id) {
            $locationModel = new Location();
            $location = $locationModel->find($franchise->location_id);
        }
        
        $this->view('franchise/location/info', [
            'pageTitle' => 'Location Information',
            'franchise' => $franchise,
            'location' => $location
        ]);
    }
}
