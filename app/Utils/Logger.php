<?php

namespace App\Utils;

class Logger
{
    protected $logPath;
    
    public function __construct($logFile = 'app.log')
    {
        $this->logPath = storage_path('logs/' . $logFile);
        
        $logDir = dirname($this->logPath);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
    }
    
    public function info($message, $context = [])
    {
        $this->log('INFO', $message, $context);
    }
    
    public function error($message, $context = [])
    {
        $this->log('ERROR', $message, $context);
    }
    
    public function warning($message, $context = [])
    {
        $this->log('WARNING', $message, $context);
    }
    
    public function debug($message, $context = [])
    {
        $this->log('DEBUG', $message, $context);
    }
    
    protected function log($level, $message, $context = [])
    {
        $timestamp = date('Y-m-d H:i:s');
        $contextStr = !empty($context) ? ' ' . json_encode($context) : '';
        
        $logEntry = "[$timestamp] [$level] $message$contextStr\n";
        
        file_put_contents($this->logPath, $logEntry, FILE_APPEND);
    }
    
    public static function logException(\Exception $e)
    {
        $logger = new self('error.log');
        $logger->error($e->getMessage(), [
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
    }
}
