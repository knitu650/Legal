<?php

namespace App\Services\Signature;

use App\Models\Signature;
use App\Models\Document;

class SignatureService
{
    public function createSignature($userId, $documentId, $signatureData, $type = 'drawn')
    {
        $signatureModel = new Signature();
        
        $signature = $signatureModel->create([
            'user_id' => $userId,
            'document_id' => $documentId,
            'signature_data' => $signatureData,
            'signature_type' => $type,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null
        ]);
        
        // Update document status
        $this->markDocumentAsSigned($documentId);
        
        return $signature;
    }
    
    public function getUserSignatures($userId)
    {
        $signatureModel = new Signature();
        return $signatureModel->where('user_id', $userId)
                             ->orderBy('signed_at', 'DESC')
                             ->get();
    }
    
    public function getDocumentSignatures($documentId)
    {
        $signatureModel = new Signature();
        return $signatureModel->where('document_id', $documentId)
                             ->orderBy('signed_at', 'DESC')
                             ->get();
    }
    
    protected function markDocumentAsSigned($documentId)
    {
        $documentModel = new Document();
        $documentModel->update($documentId, [
            'status' => DOC_STATUS_SIGNED,
            'signed_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    public function saveDefaultSignature($userId, $signatureData, $type = 'drawn')
    {
        $signatureModel = new Signature();
        
        return $signatureModel->create([
            'user_id' => $userId,
            'document_id' => null, // Default signature
            'signature_data' => $signatureData,
            'signature_type' => $type,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null
        ]);
    }
}
