<?php

namespace App\Controllers\Web;

use App\Core\Controller;
use App\Models\DocumentTemplate;
use App\Models\DocumentCategory;

class HomeController extends Controller
{
    public function index()
    {
        $templateModel = new DocumentTemplate();
        $categoryModel = new DocumentCategory();
        
        $featuredTemplates = $templateModel->where('is_active', 1)
                                          ->orderBy('created_at', 'DESC')
                                          ->limit(6)
                                          ->get();
        
        $categories = $categoryModel->where('is_active', 1)->get();
        
        $this->view('web/home/index', [
            'featuredTemplates' => $featuredTemplates,
            'categories' => $categories,
            'pageTitle' => 'Legal Document Management System'
        ]);
    }
    
    public function about()
    {
        $this->view('web/home/about', [
            'pageTitle' => 'About Us'
        ]);
    }
    
    public function contact()
    {
        $this->view('web/home/contact', [
            'pageTitle' => 'Contact Us'
        ]);
    }
    
    public function sendContact()
    {
        $data = [
            'name' => $this->request->input('name'),
            'email' => $this->request->input('email'),
            'subject' => $this->request->input('subject'),
            'message' => $this->request->input('message'),
        ];
        
        $isValid = $this->validate($data, [
            'name' => 'required|min:3',
            'email' => 'required|email',
            'subject' => 'required|min:5',
            'message' => 'required|min:10',
        ]);
        
        if (!$isValid) {
            flash('error', 'Please fill all fields correctly.');
            $this->back();
            return;
        }
        
        // Send email (implement email service)
        flash('success', 'Your message has been sent successfully!');
        $this->redirect('/contact');
    }
    
    public function pricing()
    {
        $this->view('web/home/pricing', [
            'pageTitle' => 'Pricing Plans'
        ]);
    }
}
