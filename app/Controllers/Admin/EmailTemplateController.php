<?php

namespace App\Controllers\Admin;

use App\Core\Controller;

class EmailTemplateController extends Controller
{
    protected $templates = [
        'welcome' => 'Welcome Email',
        'password_reset' => 'Password Reset',
        'document_created' => 'Document Created',
        'payment_receipt' => 'Payment Receipt',
        'consultation_booked' => 'Consultation Booked',
    ];
    
    public function index()
    {
        $this->view('admin/email-templates/index', [
            'pageTitle' => 'Email Templates',
            'templates' => $this->templates
        ]);
    }
    
    public function update($id)
    {
        $subject = $this->request->input('subject');
        $body = $this->request->input('body');
        
        // Store template (in database or file)
        
        flash('success', 'Email template updated successfully!');
        $this->redirect('/admin/email-templates');
    }
}
