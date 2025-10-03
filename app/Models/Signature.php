<?php

namespace App\Models;

use App\Core\Model;

class Signature extends Model
{
    protected $table = 'signatures';
    protected $fillable = [
        'user_id', 'document_id', 'signature_data', 
        'signature_type', 'ip_address', 'signed_at'
    ];
    
    public function user()
    {
        $userModel = new User();
        return $userModel->find($this->user_id);
    }
    
    public function document()
    {
        $documentModel = new Document();
        return $documentModel->find($this->document_id);
    }
    
    public function isDrawn()
    {
        return $this->signature_type === 'drawn';
    }
    
    public function isUploaded()
    {
        return $this->signature_type === 'uploaded';
    }
    
    public function isTyped()
    {
        return $this->signature_type === 'typed';
    }
    
    public function getSignatureImage()
    {
        if ($this->isDrawn() || $this->isTyped()) {
            return $this->signature_data; // Base64 encoded image
        }
        return asset('storage/signatures/' . $this->signature_data);
    }
    
    public function verify()
    {
        // Add signature verification logic
        return true;
    }
}
