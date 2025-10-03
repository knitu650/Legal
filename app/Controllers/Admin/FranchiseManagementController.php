<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Franchise;
use App\Models\FranchiseApplication;

class FranchiseManagementController extends Controller
{
    public function index()
    {
        $franchiseModel = new Franchise();
        $franchises = $franchiseModel->orderBy('created_at', 'DESC')->get();
        
        $this->view('admin/franchises/index', [
            'pageTitle' => 'Franchise Management',
            'franchises' => $franchises
        ]);
    }
    
    public function applications()
    {
        $applicationModel = new FranchiseApplication();
        $applications = $applicationModel->where('status', 'pending')
                                        ->orderBy('created_at', 'DESC')
                                        ->get();
        
        $this->view('admin/franchises/applications', [
            'pageTitle' => 'Franchise Applications',
            'applications' => $applications
        ]);
    }
    
    public function active()
    {
        $franchiseModel = new Franchise();
        $franchises = $franchiseModel->where('status', FRANCHISE_ACTIVE)
                                    ->orderBy('created_at', 'DESC')
                                    ->get();
        
        $this->view('admin/franchises/active', [
            'pageTitle' => 'Active Franchises',
            'franchises' => $franchises
        ]);
    }
    
    public function show($id)
    {
        $franchiseModel = new Franchise();
        $franchise = $franchiseModel->find($id);
        
        if (!$franchise) {
            flash('error', 'Franchise not found.');
            $this->redirect('/admin/franchises');
            return;
        }
        
        $this->view('admin/franchises/view', [
            'pageTitle' => 'Franchise Details',
            'franchise' => $franchise
        ]);
    }
    
    public function approve($id)
    {
        $applicationModel = new FranchiseApplication();
        $application = $applicationModel->find($id);
        
        if (!$application) {
            flash('error', 'Application not found.');
            $this->redirect('/admin/franchises/applications');
            return;
        }
        
        $user = $this->auth();
        $application->approve($user->id);
        
        // Create franchise record
        $franchiseModel = new Franchise();
        $franchiseModel->create([
            'user_id' => $application->user_id,
            'location_id' => $application->location_id,
            'business_name' => $application->business_name,
            'status' => FRANCHISE_APPROVED,
            'commission_rate' => 10, // Default
        ]);
        
        flash('success', 'Franchise application approved!');
        $this->redirect('/admin/franchises/applications');
    }
}
