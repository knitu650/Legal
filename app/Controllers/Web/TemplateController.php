<?php

namespace App\Controllers\Web;

use App\Core\Controller;
use App\Models\DocumentTemplate;
use App\Models\DocumentCategory;

class TemplateController extends Controller
{
    public function index()
    {
        $templateModel = new DocumentTemplate();
        $categoryModel = new DocumentCategory();
        
        $search = $this->request->query('search');
        $category = $this->request->query('category');
        
        $query = $templateModel->where('is_active', 1);
        
        if ($search) {
            $query = $query->where('name', 'LIKE', "%$search%");
        }
        
        if ($category) {
            $query = $query->where('category_id', $category);
        }
        
        $templates = $query->orderBy('downloads', 'DESC')->get();
        $categories = $categoryModel->where('is_active', 1)->get();
        
        $this->view('web/templates/index', [
            'pageTitle' => 'Document Templates',
            'templates' => $templates,
            'categories' => $categories
        ]);
    }
    
    public function show($id)
    {
        $templateModel = new DocumentTemplate();
        $template = $templateModel->find($id);
        
        if (!$template || !$template->isActive()) {
            flash('error', 'Template not found.');
            $this->redirect('/templates');
            return;
        }
        
        $this->view('web/templates/show', [
            'pageTitle' => $template->name,
            'template' => $template
        ]);
    }
}
