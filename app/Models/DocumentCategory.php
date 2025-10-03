<?php

namespace App\Models;

use App\Core\Model;

class DocumentCategory extends Model
{
    protected $table = 'document_categories';
    protected $fillable = [
        'name', 'slug', 'description', 'icon', 
        'parent_id', 'is_active', 'sort_order'
    ];
    
    public function templates()
    {
        $templateModel = new DocumentTemplate();
        return $templateModel->where('category_id', $this->id)->get();
    }
    
    public function documents()
    {
        $documentModel = new Document();
        return $documentModel->where('category_id', $this->id)->get();
    }
    
    public function parent()
    {
        if ($this->parent_id) {
            return $this->find($this->parent_id);
        }
        return null;
    }
    
    public function children()
    {
        return $this->where('parent_id', $this->id)->get();
    }
    
    public function isActive()
    {
        return $this->is_active == 1;
    }
    
    public function templatesCount()
    {
        $templateModel = new DocumentTemplate();
        return $templateModel->where('category_id', $this->id)->count();
    }
}
