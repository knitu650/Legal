<?php

namespace App\Controllers\Api\V1;

use App\Core\Controller;
use App\Models\Franchise;

class FranchiseApiController extends Controller
{
    public function index()
    {
        $franchiseModel = new Franchise();
        
        $locationId = $this->request->query('location_id');
        $status = $this->request->query('status');
        
        $query = $franchiseModel;
        
        if ($locationId) {
            $query = $query->where('location_id', $locationId);
        }
        
        if ($status) {
            $query = $query->where('status', $status);
        }
        
        $franchises = $query->orderBy('created_at', 'DESC')->get();
        
        $this->json([
            'success' => true,
            'data' => $franchises
        ]);
    }
}
