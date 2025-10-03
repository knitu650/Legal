<?php

namespace App\Services\Storage;

class StorageManager
{
    protected $basePath;
    
    public function __construct()
    {
        $this->basePath = storage_path('app/');
    }
    
    /**
     * Store document with automatic year/month organization
     */
    public function storeDocument($file, $userId = null)
    {
        $year = date('Y');
        $month = date('m');
        
        $path = "documents/$year/$month/";
        $directory = $this->basePath . $path;
        
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
        
        $filename = $this->generateUniqueFilename($file['name'], $userId);
        $destination = $directory . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return [
                'success' => true,
                'path' => $path . $filename,
                'full_path' => $destination,
                'url' => url('/storage/documents/' . $year . '/' . $month . '/' . $filename)
            ];
        }
        
        return ['success' => false, 'message' => 'Failed to upload file'];
    }
    
    /**
     * Store temporary document (auto-deleted after 24h)
     */
    public function storeTempDocument($file)
    {
        $path = 'documents/temp/';
        $directory = $this->basePath . $path;
        
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
        
        $filename = 'temp_' . time() . '_' . $this->sanitizeFilename($file['name']);
        $destination = $directory . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return [
                'success' => true,
                'path' => $path . $filename,
                'full_path' => $destination
            ];
        }
        
        return ['success' => false];
    }
    
    /**
     * Store signature file
     */
    public function storeSignature($signatureData, $userId)
    {
        $path = 'signatures/';
        $directory = $this->basePath . $path;
        
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
        
        $filename = 'signature_' . $userId . '_' . time() . '.png';
        $destination = $directory . $filename;
        
        // Handle base64 signature data
        if (strpos($signatureData, 'data:image') === 0) {
            $signatureData = substr($signatureData, strpos($signatureData, ',') + 1);
            $signatureData = base64_decode($signatureData);
        }
        
        if (file_put_contents($destination, $signatureData)) {
            return [
                'success' => true,
                'path' => $path . $filename,
                'full_path' => $destination,
                'url' => url('/storage/signatures/' . $filename)
            ];
        }
        
        return ['success' => false];
    }
    
    /**
     * Store uploaded file
     */
    public function storeUpload($file, $userId = null)
    {
        $path = 'uploads/';
        $directory = $this->basePath . $path;
        
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
        
        $filename = $this->generateUniqueFilename($file['name'], $userId);
        $destination = $directory . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return [
                'success' => true,
                'path' => $path . $filename,
                'full_path' => $destination,
                'size' => filesize($destination),
                'mime_type' => mime_content_type($destination)
            ];
        }
        
        return ['success' => false];
    }
    
    /**
     * Store exported report
     */
    public function storeExport($content, $filename, $format = 'csv')
    {
        $path = 'exports/';
        $directory = $this->basePath . $path;
        
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
        
        $filename = $this->sanitizeFilename($filename) . '_' . time() . '.' . $format;
        $destination = $directory . $filename;
        
        if (file_put_contents($destination, $content)) {
            return [
                'success' => true,
                'path' => $path . $filename,
                'full_path' => $destination,
                'download_url' => url('/storage/exports/' . $filename)
            ];
        }
        
        return ['success' => false];
    }
    
    /**
     * Delete file
     */
    public function delete($path)
    {
        $fullPath = $this->basePath . $path;
        
        if (file_exists($fullPath)) {
            return unlink($fullPath);
        }
        
        return false;
    }
    
    /**
     * Get file info
     */
    public function getFileInfo($path)
    {
        $fullPath = $this->basePath . $path;
        
        if (!file_exists($fullPath)) {
            return null;
        }
        
        return [
            'path' => $path,
            'full_path' => $fullPath,
            'size' => filesize($fullPath),
            'mime_type' => mime_content_type($fullPath),
            'modified' => filemtime($fullPath),
            'exists' => true
        ];
    }
    
    /**
     * Clean temporary files older than 24 hours
     */
    public function cleanTempFiles()
    {
        $tempPath = $this->basePath . 'documents/temp/';
        
        if (!is_dir($tempPath)) {
            return 0;
        }
        
        $files = glob($tempPath . '*');
        $deleted = 0;
        $now = time();
        $maxAge = 24 * 60 * 60; // 24 hours
        
        foreach ($files as $file) {
            if (is_file($file)) {
                if ($now - filemtime($file) >= $maxAge) {
                    if (unlink($file)) {
                        $deleted++;
                    }
                }
            }
        }
        
        return $deleted;
    }
    
    /**
     * Get storage usage statistics
     */
    public function getStorageStats()
    {
        return [
            'documents' => $this->getDirectorySize($this->basePath . 'documents/'),
            'signatures' => $this->getDirectorySize($this->basePath . 'signatures/'),
            'uploads' => $this->getDirectorySize($this->basePath . 'uploads/'),
            'exports' => $this->getDirectorySize($this->basePath . 'exports/'),
            'total' => $this->getDirectorySize($this->basePath)
        ];
    }
    
    /**
     * Generate unique filename
     */
    protected function generateUniqueFilename($originalName, $userId = null)
    {
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $basename = pathinfo($originalName, PATHINFO_FILENAME);
        
        $basename = $this->sanitizeFilename($basename);
        
        $prefix = $userId ? "user_{$userId}_" : '';
        
        return $prefix . $basename . '_' . time() . '_' . uniqid() . '.' . $extension;
    }
    
    /**
     * Sanitize filename
     */
    protected function sanitizeFilename($filename)
    {
        $filename = preg_replace('/[^a-zA-Z0-9._-]/', '_', $filename);
        return $filename;
    }
    
    /**
     * Get directory size in bytes
     */
    protected function getDirectorySize($path)
    {
        if (!is_dir($path)) {
            return 0;
        }
        
        $size = 0;
        
        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path)) as $file) {
            if ($file->isFile()) {
                $size += $file->getSize();
            }
        }
        
        return $size;
    }
}
