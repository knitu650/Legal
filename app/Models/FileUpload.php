<?php

namespace App\Models;

use App\Core\Model;

class FileUpload extends Model
{
    protected $table = 'file_uploads';
    protected $fillable = [
        'user_id', 'filename', 'original_filename', 'file_path',
        'file_size', 'mime_type', 'category', 'uploadable_type', 'uploadable_id'
    ];
    
    public function user()
    {
        $userModel = new User();
        return $userModel->find($this->user_id);
    }
    
    public function getUrl()
    {
        return asset('storage/' . $this->file_path);
    }
    
    public function getSize()
    {
        return $this->formatBytes($this->file_size);
    }
    
    protected function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
    
    public function isImage()
    {
        return strpos($this->mime_type, 'image/') === 0;
    }
    
    public function isPdf()
    {
        return $this->mime_type === 'application/pdf';
    }
    
    public function isDocument()
    {
        $docTypes = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ];
        return in_array($this->mime_type, $docTypes);
    }
    
    public function deleteFile()
    {
        $filePath = storage_path($this->file_path);
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        return $this->delete($this->id);
    }
}
