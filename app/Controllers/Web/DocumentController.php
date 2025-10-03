<?php

namespace App\Controllers\Web;

use App\Core\Controller;
use App\Models\Document;
use App\Models\DocumentTemplate;
use App\Models\DocumentCategory;

class DocumentController extends Controller
{
    public function index()
    {
        $categoryModel = new DocumentCategory();
        $documentModel = new Document();
        
        $search = $this->request->query('search');
        $category = $this->request->query('category');
        
        $query = $documentModel;
        
        if ($search) {
            $query = $query->where('title', 'LIKE', "%$search%");
        }
        
        if ($category) {
            $query = $query->where('category_id', $category);
        }
        
        $documents = $query->where('status', DOC_STATUS_COMPLETED)
                          ->orderBy('created_at', 'DESC')
                          ->get();
        
        $categories = $categoryModel->where('is_active', 1)->get();
        
        $this->view('web/documents/index', [
            'pageTitle' => 'Documents',
            'documents' => $documents,
            'categories' => $categories
        ]);
    }
}
