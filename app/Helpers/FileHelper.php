<?php

namespace App\Helpers;

class FileHelper
{
    public static function validateUpload($file, $allowedTypes = [], $maxSize = null)
    {
        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            return ['valid' => false, 'message' => 'Invalid file upload'];
        }
        
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['valid' => false, 'message' => 'Upload error'];
        }
        
        // Check file size
        $maxSize = $maxSize ?? config('storage.upload.max_size', 10485760); // 10MB default
        if ($file['size'] > $maxSize) {
            return ['valid' => false, 'message' => 'File too large'];
        }
        
        // Check file type
        if (!empty($allowedTypes)) {
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            if (!in_array(strtolower($extension), $allowedTypes)) {
                return ['valid' => false, 'message' => 'File type not allowed'];
            }
        }
        
        return ['valid' => true];
    }
    
    public static function getExtension($filename)
    {
        return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    }
    
    public static function getMimeType($file)
    {
        if (isset($file['type'])) {
            return $file['type'];
        }
        
        if (isset($file['tmp_name']) && file_exists($file['tmp_name'])) {
            return mime_content_type($file['tmp_name']);
        }
        
        return 'application/octet-stream';
    }
    
    public static function sanitizeFilename($filename)
    {
        $filename = preg_replace('/[^a-zA-Z0-9._-]/', '_', $filename);
        return $filename;
    }
}
