<?php

namespace App\Controllers\Franchise;

use App\Core\Controller;
use App\Models\Document;

class DocumentController extends Controller
{
    public function index()
    {
        $user = $this->auth();
        
        // Get documents created by franchise
        $documentModel = new Document();
        $documents = $documentModel->orderBy('created_at', 'DESC')->get();
        
        $this->view('franchise/documents/index', [
            'pageTitle' => 'Documents',
            'documents' => $documents
        ]);
    }
    
    public function store()
    {
        $user = $this->auth();
        
        $data = [
            'user_id' => $user->id,
            'template_id' => $this->request->input('template_id'),
            'title' => $this->request->input('title'),
            'content' => $this->request->input('content'),
            'status' => DOC_STATUS_DRAFT,
        ];
        
        $documentModel = new Document();
        $documentModel->create($data);
        
        flash('success', 'Document created successfully!');
        $this->redirect('/franchise/documents');
    }
}
