<?php

namespace App\Models;

use App\Core\Model;

class Document extends Model
{
    protected $table = 'documents';
    protected $fillable = [
        'user_id', 'template_id', 'category_id', 'title', 'content',
        'status', 'file_path', 'metadata', 'signed_at'
    ];
    
    public function user()
    {
        $userModel = new User();
        return $userModel->find($this->user_id);
    }
    
    public function template()
    {
        $templateModel = new DocumentTemplate();
        return $templateModel->find($this->template_id);
    }
    
    public function category()
    {
        $categoryModel = new DocumentCategory();
        return $categoryModel->find($this->category_id);
    }
    
    public function versions()
    {
        $versionModel = new DocumentVersion();
        return $versionModel->where('document_id', $this->id)
                            ->orderBy('version_number', 'DESC')
                            ->get();
    }
    
    public function signatures()
    {
        $signatureModel = new Signature();
        return $signatureModel->where('document_id', $this->id)->get();
    }
    
    public function isSigned()
    {
        return $this->status === DOC_STATUS_SIGNED;
    }
    
    public function isDraft()
    {
        return $this->status === DOC_STATUS_DRAFT;
    }
}
