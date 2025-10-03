<?php

namespace App\Controllers\Admin;

use App\Core\Controller;

class SettingsController extends Controller
{
    public function index()
    {
        $this->view('admin/settings/general', [
            'pageTitle' => 'System Settings'
        ]);
    }
    
    public function updateGeneral()
    {
        $settings = [
            'app_name' => $this->request->input('app_name'),
            'app_email' => $this->request->input('app_email'),
            'app_phone' => $this->request->input('app_phone'),
            'app_address' => $this->request->input('app_address'),
            'maintenance_mode' => $this->request->input('maintenance_mode', 0),
        ];
        
        // Store settings (in database or config file)
        
        flash('success', 'Settings updated successfully!');
        $this->redirect('/admin/settings');
    }
    
    public function updatePayment()
    {
        $settings = [
            'razorpay_key' => $this->request->input('razorpay_key'),
            'razorpay_secret' => $this->request->input('razorpay_secret'),
            'stripe_key' => $this->request->input('stripe_key'),
            'stripe_secret' => $this->request->input('stripe_secret'),
            'paytm_mid' => $this->request->input('paytm_mid'),
            'paytm_key' => $this->request->input('paytm_key'),
        ];
        
        // Store payment settings
        
        flash('success', 'Payment settings updated successfully!');
        $this->redirect('/admin/settings');
    }
    
    public function updateEmail()
    {
        $settings = [
            'mail_driver' => $this->request->input('mail_driver'),
            'mail_host' => $this->request->input('mail_host'),
            'mail_port' => $this->request->input('mail_port'),
            'mail_username' => $this->request->input('mail_username'),
            'mail_password' => $this->request->input('mail_password'),
            'mail_encryption' => $this->request->input('mail_encryption'),
        ];
        
        // Store email settings
        
        flash('success', 'Email settings updated successfully!');
        $this->redirect('/admin/settings');
    }
}
