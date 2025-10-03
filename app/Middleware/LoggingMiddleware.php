<?php

namespace App\Middleware;

use App\Core\Request;

class LoggingMiddleware
{
    public function handle(Request $request)
    {
        $logFile = storage_path('logs/access.log');
        $logDir = dirname($logFile);
        
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        $logEntry = sprintf(
            "[%s] %s %s - IP: %s - User-Agent: %s\n",
            date('Y-m-d H:i:s'),
            $request->getMethod(),
            $request->getUri(),
            $request->ip(),
            $request->userAgent()
        );
        
        file_put_contents($logFile, $logEntry, FILE_APPEND);
        
        return true;
    }
}
