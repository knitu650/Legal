<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Document;

class DocumentManagementController extends Controller
{
    public function index()
    {
        $documentModel = new Document();
        $status = $this->request->query('status');
        
        $query = $documentModel;
        
        if ($status) {
            $query = $query->where('status', $status);
        }
        
        $documents = $query->orderBy('created_at', 'DESC')->get();
        
        $this->view('admin/documents/index', [
            'pageTitle' => 'Document Management',
            'documents' => $documents
        ]);
    }
    
    public function show($id)
    {
        $documentModel = new Document();
        $document = $documentModel->find($id);
        
        if (!$document) {
            flash('error', 'Document not found.');
            $this->redirect('/admin/documents');
            return;
        }
        
        $this->view('admin/documents/show', [
            'pageTitle' => 'Document Details',
            'document' => $document
        ]);
    }
    
    public function approve($id)
    {
        $documentModel = new Document();
        $documentModel->update($id, [
            'status' => DOC_STATUS_COMPLETED
        ]);
        
        flash('success', 'Document approved successfully!');
        $this->redirect('/admin/documents/' . $id);
    }
    
    public function reject($id)
    {
        $documentModel = new Document();
        $documentModel->update($id, [
            'status' => DOC_STATUS_DRAFT
        ]);
        
        flash('success', 'Document rejected.');
        $this->redirect('/admin/documents/' . $id);
    }
}
