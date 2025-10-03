<?php

namespace App\Models;

use App\Core\Model;

class DocumentTemplate extends Model
{
    protected $table = 'document_templates';
    protected $fillable = [
        'category_id', 'name', 'description', 'content',
        'fields', 'price', 'is_active', 'thumbnail'
    ];
    
    public function category()
    {
        $categoryModel = new DocumentCategory();
        return $categoryModel->find($this->category_id);
    }
    
    public function getFields()
    {
        return json_decode($this->fields, true) ?? [];
    }
    
    public function isActive()
    {
        return $this->is_active == 1;
    }
}
