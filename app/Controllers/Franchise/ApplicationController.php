<?php

namespace App\Controllers\Franchise;

use App\Core\Controller;
use App\Models\FranchiseApplication;

class ApplicationController extends Controller
{
    public function index()
    {
        $user = $this->auth();
        $applicationModel = new FranchiseApplication();
        
        $application = $applicationModel->where('user_id', $user->id)->first();
        
        $this->view('franchise/application/apply', [
            'pageTitle' => 'Franchise Application',
            'application' => $application
        ]);
    }
    
    public function submit()
    {
        $user = $this->auth();
        
        $data = [
            'user_id' => $user->id,
            'business_name' => $this->request->input('business_name'),
            'location_id' => $this->request->input('location_id'),
            'investment_capacity' => $this->request->input('investment_capacity'),
            'experience' => $this->request->input('experience'),
            'proposed_territory' => json_encode($this->request->input('proposed_territory', [])),
            'status' => 'pending',
        ];
        
        $applicationModel = new FranchiseApplication();
        $applicationModel->create($data);
        
        flash('success', 'Franchise application submitted successfully!');
        $this->redirect('/franchise/application/status');
    }
    
    public function status()
    {
        $user = $this->auth();
        $applicationModel = new FranchiseApplication();
        
        $application = $applicationModel->where('user_id', $user->id)->first();
        
        $this->view('franchise/application/status', [
            'pageTitle' => 'Application Status',
            'application' => $application
        ]);
    }
}
