<?php

namespace App\Models;

use App\Core\Model;

class DocumentVersion extends Model
{
    protected $table = 'document_versions';
    protected $fillable = [
        'document_id', 'version_number', 'content', 
        'changes_summary', 'created_by'
    ];
    
    public function document()
    {
        $documentModel = new Document();
        return $documentModel->find($this->document_id);
    }
    
    public function creator()
    {
        $userModel = new User();
        return $userModel->find($this->created_by);
    }
    
    public function isLatest()
    {
        $latest = $this->where('document_id', $this->document_id)
                      ->orderBy('version_number', 'DESC')
                      ->first();
        
        return $latest && $latest->id == $this->id;
    }
    
    public static function createVersion($documentId, $content, $userId, $changesSummary = '')
    {
        $instance = new self();
        
        // Get latest version number
        $latestVersion = $instance->where('document_id', $documentId)
                                 ->orderBy('version_number', 'DESC')
                                 ->first();
        
        $versionNumber = $latestVersion ? $latestVersion->version_number + 1 : 1;
        
        return $instance->create([
            'document_id' => $documentId,
            'version_number' => $versionNumber,
            'content' => $content,
            'changes_summary' => $changesSummary,
            'created_by' => $userId
        ]);
    }
}
