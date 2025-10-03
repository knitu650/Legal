<?php

namespace App\Services\Document;

use App\Models\DocumentVersion;

class DocumentVersionService
{
    public function createVersion($documentId, $content, $userId, $changes = '')
    {
        return DocumentVersion::createVersion($documentId, $content, $userId, $changes);
    }
    
    public function getVersionHistory($documentId)
    {
        $versionModel = new DocumentVersion();
        return $versionModel->where('document_id', $documentId)
                           ->orderBy('version_number', 'DESC')
                           ->get();
    }
    
    public function compareVersions($versionId1, $versionId2)
    {
        $versionModel = new DocumentVersion();
        $version1 = $versionModel->find($versionId1);
        $version2 = $versionModel->find($versionId2);
        
        if (!$version1 || !$version2) {
            return null;
        }
        
        return [
            'version1' => $version1,
            'version2' => $version2,
            'diff' => $this->calculateDiff($version1->content, $version2->content)
        ];
    }
    
    public function restoreVersion($documentId, $versionId, $userId)
    {
        $versionModel = new DocumentVersion();
        $version = $versionModel->find($versionId);
        
        if (!$version || $version->document_id != $documentId) {
            return false;
        }
        
        // Create new version with restored content
        $this->createVersion(
            $documentId, 
            $version->content, 
            $userId, 
            "Restored to version {$version->version_number}"
        );
        
        return true;
    }
    
    protected function calculateDiff($content1, $content2)
    {
        // Simple diff calculation
        return [
            'added' => strlen($content2) - strlen($content1),
            'similarity' => similar_text($content1, $content2)
        ];
    }
}
