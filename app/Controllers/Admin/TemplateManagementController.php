<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\DocumentTemplate;
use App\Models\DocumentCategory;

class TemplateManagementController extends Controller
{
    public function index()
    {
        $templateModel = new DocumentTemplate();
        $templates = $templateModel->orderBy('created_at', 'DESC')->get();
        
        $this->view('admin/templates/index', [
            'pageTitle' => 'Template Management',
            'templates' => $templates
        ]);
    }
    
    public function create()
    {
        $categoryModel = new DocumentCategory();
        $categories = $categoryModel->where('is_active', 1)->get();
        
        $this->view('admin/templates/create', [
            'pageTitle' => 'Create Template',
            'categories' => $categories
        ]);
    }
    
    public function store()
    {
        $data = [
            'name' => $this->request->input('name'),
            'description' => $this->request->input('description'),
            'category_id' => $this->request->input('category_id'),
            'content' => $this->request->input('content'),
            'price' => $this->request->input('price', 0),
            'is_active' => $this->request->input('is_active', 1),
        ];
        
        $templateModel = new DocumentTemplate();
        $templateModel->create($data);
        
        flash('success', 'Template created successfully!');
        $this->redirect('/admin/templates');
    }
    
    public function edit($id)
    {
        $templateModel = new DocumentTemplate();
        $template = $templateModel->find($id);
        
        if (!$template) {
            flash('error', 'Template not found.');
            $this->redirect('/admin/templates');
            return;
        }
        
        $categoryModel = new DocumentCategory();
        $categories = $categoryModel->where('is_active', 1)->get();
        
        $this->view('admin/templates/edit', [
            'pageTitle' => 'Edit Template',
            'template' => $template,
            'categories' => $categories
        ]);
    }
    
    public function update($id)
    {
        $data = [
            'name' => $this->request->input('name'),
            'description' => $this->request->input('description'),
            'category_id' => $this->request->input('category_id'),
            'content' => $this->request->input('content'),
            'price' => $this->request->input('price'),
            'is_active' => $this->request->input('is_active'),
        ];
        
        $templateModel = new DocumentTemplate();
        $templateModel->update($id, $data);
        
        flash('success', 'Template updated successfully!');
        $this->redirect('/admin/templates');
    }
    
    public function delete($id)
    {
        $templateModel = new DocumentTemplate();
        $templateModel->delete($id);
        
        flash('success', 'Template deleted successfully!');
        $this->redirect('/admin/templates');
    }
}
