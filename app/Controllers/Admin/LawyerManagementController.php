<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Lawyer;

class LawyerManagementController extends Controller
{
    public function index()
    {
        $lawyerModel = new Lawyer();
        $status = $this->request->query('status');
        
        $query = $lawyerModel;
        
        if ($status) {
            $query = $query->where('verification_status', $status);
        }
        
        $lawyers = $query->orderBy('created_at', 'DESC')->get();
        
        $this->view('admin/lawyers/index', [
            'pageTitle' => 'Lawyer Management',
            'lawyers' => $lawyers
        ]);
    }
    
    public function pending()
    {
        $lawyerModel = new Lawyer();
        $lawyers = $lawyerModel->where('verification_status', 'pending')
                              ->orderBy('created_at', 'DESC')
                              ->get();
        
        $this->view('admin/lawyers/pending-approval', [
            'pageTitle' => 'Pending Lawyer Approvals',
            'lawyers' => $lawyers
        ]);
    }
    
    public function show($id)
    {
        $lawyerModel = new Lawyer();
        $lawyer = $lawyerModel->find($id);
        
        if (!$lawyer) {
            flash('error', 'Lawyer not found.');
            $this->redirect('/admin/lawyers');
            return;
        }
        
        $this->view('admin/lawyers/view', [
            'pageTitle' => 'Lawyer Details',
            'lawyer' => $lawyer
        ]);
    }
    
    public function approve($id)
    {
        $lawyerModel = new Lawyer();
        $lawyer = $lawyerModel->find($id);
        
        if (!$lawyer) {
            flash('error', 'Lawyer not found.');
            $this->redirect('/admin/lawyers');
            return;
        }
        
        $lawyer->verify();
        
        // Send approval email
        flash('success', 'Lawyer approved successfully!');
        $this->redirect('/admin/lawyers/' . $id);
    }
    
    public function reject($id)
    {
        $lawyerModel = new Lawyer();
        $lawyer = $lawyerModel->find($id);
        
        if (!$lawyer) {
            flash('error', 'Lawyer not found.');
            $this->redirect('/admin/lawyers');
            return;
        }
        
        $lawyer->reject();
        
        // Send rejection email
        flash('success', 'Lawyer rejected.');
        $this->redirect('/admin/lawyers/' . $id);
    }
}
