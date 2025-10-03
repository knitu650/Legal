<?php

namespace App\Controllers\Franchise;

use App\Core\Controller;

class StaffController extends Controller
{
    public function index()
    {
        $staff = [];
        
        $this->view('franchise/staff/index', [
            'pageTitle' => 'Staff Management',
            'staff' => $staff
        ]);
    }
    
    public function store()
    {
        $data = [
            'name' => $this->request->input('name'),
            'email' => $this->request->input('email'),
            'role' => $this->request->input('role'),
        ];
        
        // Add staff member
        
        flash('success', 'Staff member added successfully!');
        $this->redirect('/franchise/staff');
    }
}
