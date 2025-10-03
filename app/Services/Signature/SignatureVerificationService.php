<?php

namespace App\Services\Signature;

use App\Models\Signature;

class SignatureVerificationService
{
    public function verifySignature($signatureId)
    {
        $signatureModel = new Signature();
        $signature = $signatureModel->find($signatureId);
        
        if (!$signature) {
            return [
                'valid' => false,
                'message' => 'Signature not found'
            ];
        }
        
        // Verify signature hasn't been tampered with
        $isValid = $this->checkIntegrity($signature);
        
        return [
            'valid' => $isValid,
            'signature' => $signature,
            'signed_at' => $signature->signed_at,
            'signer' => $signature->user(),
            'ip_address' => $signature->ip_address
        ];
    }
    
    protected function checkIntegrity($signature)
    {
        // In production, implement cryptographic verification
        return true;
    }
    
    public function generateCertificate($signatureId)
    {
        $signature = $this->verifySignature($signatureId);
        
        if (!$signature['valid']) {
            return null;
        }
        
        return [
            'certificate_id' => 'CERT-' . time() . '-' . $signatureId,
            'issued_at' => date('Y-m-d H:i:s'),
            'valid_until' => date('Y-m-d H:i:s', strtotime('+10 years')),
            'signature_hash' => hash('sha256', $signature['signature']->signature_data),
            'verification_url' => url('/verify/signature/' . $signatureId)
        ];
    }
}
