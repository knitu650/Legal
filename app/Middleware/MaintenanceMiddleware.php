<?php

namespace App\Middleware;

use App\Core\Request;

class MaintenanceMiddleware
{
    public function handle(Request $request)
    {
        $maintenanceMode = env('MAINTENANCE_MODE', false);
        
        if ($maintenanceMode) {
            // Allow access for admins
            $allowedIps = [
                '127.0.0.1',
                '::1'
            ];
            
            if (!in_array($request->ip(), $allowedIps)) {
                http_response_code(503);
                
                if ($request->isJson()) {
                    header('Content-Type: application/json');
                    echo json_encode([
                        'error' => 'Service Unavailable',
                        'message' => 'System is currently under maintenance. Please try again later.'
                    ]);
                } else {
                    require __DIR__ . '/../../resources/views/errors/maintenance.php';
                }
                
                exit;
            }
        }
        
        return true;
    }
}
