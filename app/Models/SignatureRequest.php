<?php

namespace App\Models;

use App\Core\Model;

class SignatureRequest extends Model
{
    protected $table = 'signature_requests';
    protected $fillable = [
        'document_id', 'sender_id', 'recipient_email', 
        'recipient_name', 'status', 'token', 'expires_at', 'signed_at'
    ];
    
    public function document()
    {
        $documentModel = new Document();
        return $documentModel->find($this->document_id);
    }
    
    public function sender()
    {
        $userModel = new User();
        return $userModel->find($this->sender_id);
    }
    
    public function isPending()
    {
        return $this->status === 'pending';
    }
    
    public function isSigned()
    {
        return $this->status === 'signed';
    }
    
    public function isDeclined()
    {
        return $this->status === 'declined';
    }
    
    public function isExpired()
    {
        return strtotime($this->expires_at) < time();
    }
    
    public function markAsSigned()
    {
        return $this->update($this->id, [
            'status' => 'signed',
            'signed_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    public function decline()
    {
        return $this->update($this->id, [
            'status' => 'declined'
        ]);
    }
    
    public static function generateToken()
    {
        return bin2hex(random_bytes(32));
    }
}
