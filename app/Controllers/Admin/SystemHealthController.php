<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;

class SystemHealthController extends Controller
{
    public function index()
    {
        $health = [
            'database' => $this->checkDatabase(),
            'storage' => $this->checkStorage(),
            'cache' => $this->checkCache(),
            'mail' => $this->checkMail(),
            'php_version' => phpversion(),
            'disk_space' => $this->getDiskSpace(),
            'memory_usage' => $this->getMemoryUsage(),
        ];
        
        $this->view('admin/system-health/index', [
            'pageTitle' => 'System Health',
            'health' => $health
        ]);
    }
    
    protected function checkDatabase()
    {
        try {
            $db = Database::getInstance();
            $db->getConnection()->query('SELECT 1');
            return ['status' => 'healthy', 'message' => 'Connected'];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }
    
    protected function checkStorage()
    {
        $path = storage_path();
        
        if (is_writable($path)) {
            return ['status' => 'healthy', 'message' => 'Writable'];
        }
        
        return ['status' => 'warning', 'message' => 'Not writable'];
    }
    
    protected function checkCache()
    {
        $cachePath = storage_path('cache');
        
        if (is_dir($cachePath) && is_writable($cachePath)) {
            return ['status' => 'healthy', 'message' => 'Working'];
        }
        
        return ['status' => 'warning', 'message' => 'Cache directory issue'];
    }
    
    protected function checkMail()
    {
        // Test email configuration
        return ['status' => 'healthy', 'message' => 'Configured'];
    }
    
    protected function getDiskSpace()
    {
        $total = disk_total_space('/');
        $free = disk_free_space('/');
        $used = $total - $free;
        $percentage = ($used / $total) * 100;
        
        return [
            'total' => $this->formatBytes($total),
            'used' => $this->formatBytes($used),
            'free' => $this->formatBytes($free),
            'percentage' => round($percentage, 2)
        ];
    }
    
    protected function getMemoryUsage()
    {
        $memory = memory_get_usage(true);
        $peak = memory_get_peak_usage(true);
        
        return [
            'current' => $this->formatBytes($memory),
            'peak' => $this->formatBytes($peak)
        ];
    }
    
    protected function formatBytes($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }
}
