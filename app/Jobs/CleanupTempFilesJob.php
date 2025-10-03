<?php

namespace App\Jobs;

class CleanupTempFilesJob
{
    public function handle()
    {
        $tempPath = storage_path('app/temp/');
        
        if (!is_dir($tempPath)) {
            return;
        }
        
        $files = glob($tempPath . '*');
        $now = time();
        $maxAge = 24 * 60 * 60; // 24 hours
        
        foreach ($files as $file) {
            if (is_file($file)) {
                if ($now - filemtime($file) >= $maxAge) {
                    unlink($file);
                }
            }
        }
    }
    
    public function dispatch()
    {
        return $this->handle();
    }
}
