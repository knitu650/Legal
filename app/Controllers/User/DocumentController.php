<?php

namespace App\Controllers\User;

use App\Core\Controller;
use App\Models\Document;
use App\Models\DocumentTemplate;
use App\Models\DocumentCategory;

class DocumentController extends Controller
{
    public function index()
    {
        $user = $this->auth();
        $page = $this->request->query('page', 1);
        
        $documentModel = new Document();
        $documents = $documentModel->where('user_id', $user->id)
                                  ->orderBy('created_at', 'DESC')
                                  ->get();
        
        $this->view('user/documents/index', [
            'pageTitle' => 'My Documents',
            'documents' => $documents,
        ]);
    }
    
    public function create()
    {
        $templateId = $this->request->query('template_id');
        
        $templateModel = new DocumentTemplate();
        $categoryModel = new DocumentCategory();
        
        $template = null;
        if ($templateId) {
            $template = $templateModel->find($templateId);
        }
        
        $templates = $templateModel->where('is_active', 1)->get();
        $categories = $categoryModel->where('is_active', 1)->get();
        
        $this->view('user/documents/create', [
            'pageTitle' => 'Create Document',
            'template' => $template,
            'templates' => $templates,
            'categories' => $categories,
        ]);
    }
    
    public function store()
    {
        $user = $this->auth();
        
        $data = [
            'template_id' => $this->request->input('template_id'),
            'title' => $this->request->input('title'),
            'content' => $this->request->input('content'),
            'category_id' => $this->request->input('category_id'),
        ];
        
        $isValid = $this->validate($data, [
            'title' => 'required|min:3',
            'content' => 'required|min:10',
        ]);
        
        if (!$isValid) {
            flash('error', 'Please fill all required fields.');
            $this->back();
            return;
        }
        
        $documentModel = new Document();
        $document = $documentModel->create([
            'user_id' => $user->id,
            'template_id' => $data['template_id'],
            'category_id' => $data['category_id'],
            'title' => $data['title'],
            'content' => $data['content'],
            'status' => DOC_STATUS_DRAFT,
        ]);
        
        flash('success', 'Document created successfully!');
        $this->redirect('/user/documents/' . $document->id);
    }
    
    public function show($id)
    {
        $user = $this->auth();
        
        $documentModel = new Document();
        $document = $documentModel->find($id);
        
        if (!$document || $document->user_id != $user->id) {
            flash('error', 'Document not found.');
            $this->redirect('/user/documents');
            return;
        }
        
        $this->view('user/documents/view', [
            'pageTitle' => $document->title,
            'document' => $document,
        ]);
    }
    
    public function edit($id)
    {
        $user = $this->auth();
        
        $documentModel = new Document();
        $document = $documentModel->find($id);
        
        if (!$document || $document->user_id != $user->id) {
            flash('error', 'Document not found.');
            $this->redirect('/user/documents');
            return;
        }
        
        $categoryModel = new DocumentCategory();
        $categories = $categoryModel->where('is_active', 1)->get();
        
        $this->view('user/documents/edit', [
            'pageTitle' => 'Edit Document',
            'document' => $document,
            'categories' => $categories,
        ]);
    }
    
    public function update($id)
    {
        $user = $this->auth();
        
        $documentModel = new Document();
        $document = $documentModel->find($id);
        
        if (!$document || $document->user_id != $user->id) {
            $this->json(['error' => 'Document not found'], 404);
            return;
        }
        
        $data = [
            'title' => $this->request->input('title'),
            'content' => $this->request->input('content'),
            'status' => $this->request->input('status', $document->status),
        ];
        
        $documentModel->update($id, $data);
        
        flash('success', 'Document updated successfully!');
        $this->redirect('/user/documents/' . $id);
    }
    
    public function delete($id)
    {
        $user = $this->auth();
        
        $documentModel = new Document();
        $document = $documentModel->find($id);
        
        if (!$document || $document->user_id != $user->id) {
            $this->json(['error' => 'Document not found'], 404);
            return;
        }
        
        $documentModel->delete($id);
        
        flash('success', 'Document deleted successfully!');
        $this->redirect('/user/documents');
    }
    
    public function download($id)
    {
        $user = $this->auth();
        
        $documentModel = new Document();
        $document = $documentModel->find($id);
        
        if (!$document || $document->user_id != $user->id) {
            flash('error', 'Document not found.');
            $this->redirect('/user/documents');
            return;
        }
        
        // Generate PDF and download
        // Implementation with PDF library
        flash('info', 'Document download feature coming soon!');
        $this->back();
    }
}
