<?php

namespace App\Services\Signature;

use App\Models\SignatureRequest;
use App\Services\Notification\EmailService;

class SignatureRequestService
{
    protected $emailService;
    
    public function __construct()
    {
        $this->emailService = new EmailService();
    }
    
    public function requestSignature($documentId, $senderId, $recipientEmail, $recipientName = null)
    {
        $token = SignatureRequest::generateToken();
        $expiresAt = date('Y-m-d H:i:s', strtotime('+7 days'));
        
        $requestModel = new SignatureRequest();
        $request = $requestModel->create([
            'document_id' => $documentId,
            'sender_id' => $senderId,
            'recipient_email' => $recipientEmail,
            'recipient_name' => $recipientName,
            'status' => 'pending',
            'token' => $token,
            'expires_at' => $expiresAt
        ]);
        
        // Send email
        $this->sendSignatureRequestEmail($request);
        
        return $request;
    }
    
    protected function sendSignatureRequestEmail($request)
    {
        $signUrl = url('/sign/' . $request->token);
        
        $this->emailService->send(
            $request->recipient_email,
            'Signature Request',
            "You have been requested to sign a document. Click here to sign: $signUrl"
        );
    }
    
    public function getRequestByToken($token)
    {
        $requestModel = new SignatureRequest();
        return $requestModel->where('token', $token)->first();
    }
    
    public function markAsSigned($requestId)
    {
        $requestModel = new SignatureRequest();
        $request = $requestModel->find($requestId);
        
        if ($request) {
            $request->markAsSigned();
            
            // Notify sender
            $this->notifySender($request);
        }
    }
    
    protected function notifySender($request)
    {
        // Send notification to sender that document was signed
    }
}
