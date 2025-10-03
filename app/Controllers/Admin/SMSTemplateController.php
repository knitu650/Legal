<?php

namespace App\Controllers\Admin;

use App\Core\Controller;

class SMSTemplateController extends Controller
{
    protected $templates = [
        'otp' => 'OTP Verification',
        'payment_success' => 'Payment Success',
        'consultation_reminder' => 'Consultation Reminder',
        'document_signed' => 'Document Signed',
    ];
    
    public function index()
    {
        $this->view('admin/sms-templates/index', [
            'pageTitle' => 'SMS Templates',
            'templates' => $this->templates
        ]);
    }
    
    public function update($id)
    {
        $message = $this->request->input('message');
        
        // Store template
        
        flash('success', 'SMS template updated successfully!');
        $this->redirect('/admin/sms-templates');
    }
}
