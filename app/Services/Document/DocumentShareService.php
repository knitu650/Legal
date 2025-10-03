<?php

namespace App\Services\Document;

use App\Models\DocumentCollaborator;
use App\Services\Notification\EmailService;

class DocumentShareService
{
    protected $emailService;
    
    public function __construct()
    {
        $this->emailService = new EmailService();
    }
    
    public function shareWithUser($documentId, $userId, $permission = 'view', $sharedBy)
    {
        $collaboratorModel = new DocumentCollaborator();
        
        // Check if already shared
        $existing = $collaboratorModel->where('document_id', $documentId)
                                     ->where('user_id', $userId)
                                     ->first();
        
        if ($existing) {
            // Update permission
            $collaboratorModel->update($existing->id, [
                'permission_level' => $permission
            ]);
            
            return $existing;
        }
        
        // Create new share
        $collaborator = $collaboratorModel->create([
            'document_id' => $documentId,
            'user_id' => $userId,
            'permission_level' => $permission,
            'invited_by' => $sharedBy
        ]);
        
        // Send notification
        $this->notifyUser($userId, $documentId, $sharedBy);
        
        return $collaborator;
    }
    
    public function shareViaEmail($documentId, $email, $permission = 'view', $sharedBy)
    {
        // Generate share link
        $token = bin2hex(random_bytes(32));
        
        // Send email with share link
        $this->emailService->send($email, 'Document Shared With You', 
            "A document has been shared with you. Access it here: " . url('/documents/shared/' . $token)
        );
        
        return $token;
    }
    
    public function revokeAccess($documentId, $userId)
    {
        $collaboratorModel = new DocumentCollaborator();
        $collaborator = $collaboratorModel->where('document_id', $documentId)
                                         ->where('user_id', $userId)
                                         ->first();
        
        if ($collaborator) {
            $collaboratorModel->delete($collaborator->id);
            return true;
        }
        
        return false;
    }
    
    protected function notifyUser($userId, $documentId, $sharedBy)
    {
        // Send notification to user
    }
}
