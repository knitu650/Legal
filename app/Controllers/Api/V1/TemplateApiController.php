<?php

namespace App\Controllers\Api\V1;

use App\Core\Controller;
use App\Models\DocumentTemplate;

class TemplateApiController extends Controller
{
    public function index()
    {
        $templateModel = new DocumentTemplate();
        
        $categoryId = $this->request->query('category_id');
        $search = $this->request->query('search');
        
        $query = $templateModel->where('is_active', 1);
        
        if ($categoryId) {
            $query = $query->where('category_id', $categoryId);
        }
        
        if ($search) {
            $query = $query->where('name', 'LIKE', "%$search%");
        }
        
        $templates = $query->orderBy('downloads', 'DESC')->get();
        
        $this->json([
            'success' => true,
            'data' => $templates
        ]);
    }
    
    public function show($id)
    {
        $templateModel = new DocumentTemplate();
        $template = $templateModel->find($id);
        
        if (!$template || !$template->isActive()) {
            $this->json(['error' => 'Template not found'], 404);
            return;
        }
        
        $this->json($template);
    }
}
