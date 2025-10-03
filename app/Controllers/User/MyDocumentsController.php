<?php

namespace App\Controllers\User;

use App\Core\Controller;
use App\Models\Document;

class MyDocumentsController extends Controller
{
    public function index()
    {
        $user = $this->auth();
        $status = $this->request->query('status');
        
        $documentModel = new Document();
        $query = $documentModel->where('user_id', $user->id);
        
        if ($status) {
            $query = $query->where('status', $status);
        }
        
        $documents = $query->orderBy('created_at', 'DESC')->get();
        
        $this->view('user/my-documents/index', [
            'pageTitle' => 'My Documents',
            'documents' => $documents
        ]);
    }
    
    public function drafts()
    {
        $user = $this->auth();
        $documentModel = new Document();
        
        $documents = $documentModel->where('user_id', $user->id)
                                  ->where('status', DOC_STATUS_DRAFT)
                                  ->orderBy('updated_at', 'DESC')
                                  ->get();
        
        $this->view('user/my-documents/drafts', [
            'pageTitle' => 'Draft Documents',
            'documents' => $documents
        ]);
    }
    
    public function completed()
    {
        $user = $this->auth();
        $documentModel = new Document();
        
        $documents = $documentModel->where('user_id', $user->id)
                                  ->where('status', DOC_STATUS_COMPLETED)
                                  ->orderBy('created_at', 'DESC')
                                  ->get();
        
        $this->view('user/my-documents/completed', [
            'pageTitle' => 'Completed Documents',
            'documents' => $documents
        ]);
    }
}
