<?php

namespace App\Services\Document;

use App\Models\Document;
use App\Models\DocumentVersion;

class DocumentService
{
    public function createDocument($userId, $templateId, $title, $content, $categoryId = null)
    {
        $documentModel = new Document();
        
        $document = $documentModel->create([
            'user_id' => $userId,
            'template_id' => $templateId,
            'category_id' => $categoryId,
            'title' => $title,
            'content' => $content,
            'status' => DOC_STATUS_DRAFT
        ]);
        
        // Create initial version
        DocumentVersion::createVersion($document->id, $content, $userId, 'Initial version');
        
        return $document;
    }
    
    public function updateDocument($documentId, $content, $userId, $changesSummary = '')
    {
        $documentModel = new Document();
        
        $documentModel->update($documentId, [
            'content' => $content
        ]);
        
        // Create new version
        DocumentVersion::createVersion($documentId, $content, $userId, $changesSummary);
        
        return $documentModel->find($documentId);
    }
    
    public function shareDocument($documentId, $userIds, $permissionLevel = 'view')
    {
        // Implementation for sharing documents with other users
        // Add collaborators
        
        return true;
    }
    
    public function getDocumentVersions($documentId)
    {
        $versionModel = new DocumentVersion();
        return $versionModel->where('document_id', $documentId)
                           ->orderBy('version_number', 'DESC')
                           ->get();
    }
    
    public function revertToVersion($documentId, $versionId, $userId)
    {
        $versionModel = new DocumentVersion();
        $version = $versionModel->find($versionId);
        
        if (!$version || $version->document_id != $documentId) {
            return false;
        }
        
        $documentModel = new Document();
        $documentModel->update($documentId, [
            'content' => $version->content
        ]);
        
        // Create new version marking revert
        DocumentVersion::createVersion(
            $documentId, 
            $version->content, 
            $userId, 
            "Reverted to version {$version->version_number}"
        );
        
        return true;
    }
}
