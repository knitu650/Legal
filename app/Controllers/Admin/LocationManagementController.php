<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Location;

class LocationManagementController extends Controller
{
    public function index()
    {
        $locationModel = new Location();
        $locations = $locationModel->orderBy('state', 'ASC')->get();
        
        $this->view('admin/locations/index', [
            'pageTitle' => 'Location Management',
            'locations' => $locations
        ]);
    }
    
    public function create()
    {
        $this->view('admin/locations/create', [
            'pageTitle' => 'Add Location'
        ]);
    }
    
    public function store()
    {
        $data = [
            'name' => $this->request->input('name'),
            'state' => $this->request->input('state'),
            'city' => $this->request->input('city'),
            'pincode' => $this->request->input('pincode'),
            'latitude' => $this->request->input('latitude'),
            'longitude' => $this->request->input('longitude'),
            'is_active' => $this->request->input('is_active', 1),
        ];
        
        $locationModel = new Location();
        $locationModel->create($data);
        
        flash('success', 'Location added successfully!');
        $this->redirect('/admin/locations');
    }
    
    public function update($id)
    {
        $data = [
            'name' => $this->request->input('name'),
            'state' => $this->request->input('state'),
            'city' => $this->request->input('city'),
            'pincode' => $this->request->input('pincode'),
            'latitude' => $this->request->input('latitude'),
            'longitude' => $this->request->input('longitude'),
            'is_active' => $this->request->input('is_active'),
        ];
        
        $locationModel = new Location();
        $locationModel->update($id, $data);
        
        flash('success', 'Location updated successfully!');
        $this->redirect('/admin/locations');
    }
    
    public function coverage()
    {
        $locationModel = new Location();
        $locations = $locationModel->where('is_active', 1)->get();
        
        $this->view('admin/locations/coverage', [
            'pageTitle' => 'Service Coverage',
            'locations' => $locations
        ]);
    }
}
