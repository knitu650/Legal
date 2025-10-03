<?php

namespace App\Controllers\Franchise;

use App\Core\Controller;
use App\Models\User;

class CustomerController extends Controller
{
    public function index()
    {
        $userModel = new User();
        $customers = $userModel->where('role_id', ROLE_USER)->get();
        
        $this->view('franchise/customers/index', [
            'pageTitle' => 'Customers',
            'customers' => $customers
        ]);
    }
    
    public function store()
    {
        $data = [
            'name' => $this->request->input('name'),
            'email' => $this->request->input('email'),
            'phone' => $this->request->input('phone'),
            'password' => password_hash($this->request->input('password'), PASSWORD_BCRYPT),
            'role_id' => ROLE_USER,
            'status' => 'active',
        ];
        
        $userModel = new User();
        $userModel->create($data);
        
        flash('success', 'Customer added successfully!');
        $this->redirect('/franchise/customers');
    }
    
    public function show($id)
    {
        $userModel = new User();
        $customer = $userModel->find($id);
        
        $this->view('franchise/customers/view', [
            'pageTitle' => 'Customer Details',
            'customer' => $customer
        ]);
    }
}
