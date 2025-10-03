<?php

namespace App\Controllers\User;

use App\Core\Controller;
use App\Models\Signature;
use App\Models\Document;

class SignatureController extends Controller
{
    public function index()
    {
        $user = $this->auth();
        $signatureModel = new Signature();
        
        $signatures = $signatureModel->where('user_id', $user->id)
                                    ->orderBy('signed_at', 'DESC')
                                    ->get();
        
        $this->view('user/signatures/index', [
            'pageTitle' => 'My Signatures',
            'signatures' => $signatures
        ]);
    }
    
    public function create()
    {
        $user = $this->auth();
        
        $signatureData = $this->request->input('signature_data');
        $signatureType = $this->request->input('signature_type', 'drawn');
        
        if (!$signatureData) {
            $this->json(['error' => 'Signature data is required'], 400);
            return;
        }
        
        $signatureModel = new Signature();
        $signature = $signatureModel->create([
            'user_id' => $user->id,
            'document_id' => null, // Default signature
            'signature_data' => $signatureData,
            'signature_type' => $signatureType,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null
        ]);
        
        $this->json([
            'success' => true,
            'signature_id' => $signature->id
        ]);
    }
    
    public function sign($documentId)
    {
        $user = $this->auth();
        
        $documentModel = new Document();
        $document = $documentModel->find($documentId);
        
        if (!$document || $document->user_id != $user->id) {
            $this->json(['error' => 'Document not found'], 404);
            return;
        }
        
        $signatureData = $this->request->input('signature_data');
        $signatureType = $this->request->input('signature_type', 'drawn');
        
        $signatureModel = new Signature();
        $signature = $signatureModel->create([
            'user_id' => $user->id,
            'document_id' => $documentId,
            'signature_data' => $signatureData,
            'signature_type' => $signatureType,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null
        ]);
        
        // Update document status
        $documentModel->update($documentId, [
            'status' => DOC_STATUS_SIGNED,
            'signed_at' => date('Y-m-d H:i:s')
        ]);
        
        $this->json([
            'success' => true,
            'message' => 'Document signed successfully'
        ]);
    }
}
