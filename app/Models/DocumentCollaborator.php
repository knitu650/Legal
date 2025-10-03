<?php

namespace App\Models;

use App\Core\Model;

class DocumentCollaborator extends Model
{
    protected $table = 'document_collaborators';
    protected $fillable = [
        'document_id', 'user_id', 'permission_level', 
        'invited_by', 'accepted_at'
    ];
    
    public function document()
    {
        $documentModel = new Document();
        return $documentModel->find($this->document_id);
    }
    
    public function user()
    {
        $userModel = new User();
        return $userModel->find($this->user_id);
    }
    
    public function inviter()
    {
        $userModel = new User();
        return $userModel->find($this->invited_by);
    }
    
    public function canEdit()
    {
        return in_array($this->permission_level, ['edit', 'admin']);
    }
    
    public function canView()
    {
        return in_array($this->permission_level, ['view', 'edit', 'admin']);
    }
    
    public function hasAccepted()
    {
        return !is_null($this->accepted_at);
    }
    
    public function accept()
    {
        return $this->update($this->id, [
            'accepted_at' => date('Y-m-d H:i:s')
        ]);
    }
}
