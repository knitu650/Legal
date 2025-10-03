<?php

namespace App\Controllers\Api\V1;

use App\Core\Controller;
use App\Models\Document;
use App\Services\Document\DocumentService;

class DocumentApiController extends Controller
{
    protected $documentService;
    
    public function __construct($request, $response)
    {
        parent::__construct($request, $response);
        $this->documentService = new DocumentService();
    }
    
    public function index()
    {
        if (!$this->isAuthenticated()) {
            $this->json(['error' => 'Unauthenticated'], 401);
            return;
        }
        
        $user = $this->auth();
        $page = $this->request->query('page', 1);
        $perPage = $this->request->query('per_page', 15);
        
        $documentModel = new Document();
        $result = $documentModel->where('user_id', $user->id)
                               ->orderBy('created_at', 'DESC')
                               ->paginate($perPage, $page);
        
        $this->json($result);
    }
    
    public function store()
    {
        if (!$this->isAuthenticated()) {
            $this->json(['error' => 'Unauthenticated'], 401);
            return;
        }
        
        $user = $this->auth();
        
        $document = $this->documentService->createDocument(
            $user->id,
            $this->request->input('template_id'),
            $this->request->input('title'),
            $this->request->input('content'),
            $this->request->input('category_id')
        );
        
        $this->json([
            'success' => true,
            'document' => $document
        ], 201);
    }
    
    public function show($id)
    {
        if (!$this->isAuthenticated()) {
            $this->json(['error' => 'Unauthenticated'], 401);
            return;
        }
        
        $user = $this->auth();
        $documentModel = new Document();
        $document = $documentModel->find($id);
        
        if (!$document || $document->user_id != $user->id) {
            $this->json(['error' => 'Document not found'], 404);
            return;
        }
        
        $this->json($document);
    }
    
    public function update($id)
    {
        if (!$this->isAuthenticated()) {
            $this->json(['error' => 'Unauthenticated'], 401);
            return;
        }
        
        $user = $this->auth();
        $documentModel = new Document();
        $document = $documentModel->find($id);
        
        if (!$document || $document->user_id != $user->id) {
            $this->json(['error' => 'Document not found'], 404);
            return;
        }
        
        $content = $this->request->input('content');
        $updatedDocument = $this->documentService->updateDocument($id, $content, $user->id);
        
        $this->json([
            'success' => true,
            'document' => $updatedDocument
        ]);
    }
    
    public function delete($id)
    {
        if (!$this->isAuthenticated()) {
            $this->json(['error' => 'Unauthenticated'], 401);
            return;
        }
        
        $user = $this->auth();
        $documentModel = new Document();
        $document = $documentModel->find($id);
        
        if (!$document || $document->user_id != $user->id) {
            $this->json(['error' => 'Document not found'], 404);
            return;
        }
        
        $documentModel->delete($id);
        
        $this->json(['success' => true, 'message' => 'Document deleted']);
    }
}
