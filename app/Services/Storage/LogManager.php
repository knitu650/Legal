<?php

namespace App\Services\Storage;

class LogManager
{
    protected $logPath;
    
    public function __construct()
    {
        $this->logPath = storage_path('logs/');
        
        if (!is_dir($this->logPath)) {
            mkdir($this->logPath, 0755, true);
        }
    }
    
    /**
     * Write to application log
     */
    public function app($level, $message, $context = [])
    {
        $this->write('app.log', $level, $message, $context);
    }
    
    /**
     * Write to error log
     */
    public function error($message, $context = [])
    {
        $this->write('error.log', 'ERROR', $message, $context);
    }
    
    /**
     * Write to access log
     */
    public function access($method, $uri, $statusCode, $ip, $userId = null)
    {
        $context = [
            'method' => $method,
            'uri' => $uri,
            'status' => $statusCode,
            'ip' => $ip,
            'user_id' => $userId,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null
        ];
        
        $this->write('access.log', 'ACCESS', "$method $uri - $statusCode", $context);
    }
    
    /**
     * Write to payment log
     */
    public function payment($type, $message, $context = [])
    {
        $this->write('payment.log', strtoupper($type), $message, $context);
    }
    
    /**
     * Write to API log
     */
    public function api($endpoint, $method, $statusCode, $responseTime, $context = [])
    {
        $context = array_merge($context, [
            'endpoint' => $endpoint,
            'method' => $method,
            'status' => $statusCode,
            'response_time' => $responseTime . 'ms'
        ]);
        
        $this->write('api.log', 'API', "$method $endpoint - $statusCode ({$responseTime}ms)", $context);
    }
    
    /**
     * Write to audit log
     */
    public function audit($userId, $action, $resource, $details = [])
    {
        $context = [
            'user_id' => $userId,
            'action' => $action,
            'resource' => $resource,
            'details' => $details,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? null
        ];
        
        $this->write('audit.log', 'AUDIT', "$action on $resource by user $userId", $context);
    }
    
    /**
     * Generic write method
     */
    protected function write($file, $level, $message, $context = [])
    {
        $timestamp = date('Y-m-d H:i:s');
        $contextStr = !empty($context) ? ' ' . json_encode($context) : '';
        
        $logEntry = "[$timestamp] [$level] $message$contextStr" . PHP_EOL;
        
        file_put_contents($this->logPath . $file, $logEntry, FILE_APPEND | LOCK_EX);
    }
    
    /**
     * Read log file
     */
    public function read($file, $lines = 100)
    {
        $filePath = $this->logPath . $file;
        
        if (!file_exists($filePath)) {
            return [];
        }
        
        $content = file($filePath);
        return array_slice($content, -$lines);
    }
    
    /**
     * Clear log file
     */
    public function clear($file)
    {
        $filePath = $this->logPath . $file;
        
        if (file_exists($filePath)) {
            return file_put_contents($filePath, '') !== false;
        }
        
        return false;
    }
    
    /**
     * Get log file size
     */
    public function getSize($file)
    {
        $filePath = $this->logPath . $file;
        
        if (file_exists($filePath)) {
            return filesize($filePath);
        }
        
        return 0;
    }
    
    /**
     * Rotate logs (keep last 30 days)
     */
    public function rotate()
    {
        $files = glob($this->logPath . '*.log');
        
        foreach ($files as $file) {
            if (filemtime($file) < strtotime('-30 days')) {
                // Archive old log
                $archiveName = basename($file, '.log') . '_' . date('Ymd', filemtime($file)) . '.log';
                rename($file, $this->logPath . 'archive/' . $archiveName);
            }
        }
    }
    
    /**
     * Get all log files with stats
     */
    public function getAllLogs()
    {
        $logs = [];
        $files = [
            'app.log',
            'error.log',
            'access.log',
            'payment.log',
            'api.log',
            'audit.log'
        ];
        
        foreach ($files as $file) {
            $filePath = $this->logPath . $file;
            
            if (file_exists($filePath)) {
                $logs[$file] = [
                    'size' => filesize($filePath),
                    'size_formatted' => $this->formatBytes(filesize($filePath)),
                    'modified' => filemtime($filePath),
                    'modified_formatted' => date('Y-m-d H:i:s', filemtime($filePath)),
                    'lines' => count(file($filePath))
                ];
            } else {
                $logs[$file] = [
                    'size' => 0,
                    'size_formatted' => '0 B',
                    'modified' => null,
                    'modified_formatted' => 'Never',
                    'lines' => 0
                ];
            }
        }
        
        return $logs;
    }
    
    protected function formatBytes($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }
}
