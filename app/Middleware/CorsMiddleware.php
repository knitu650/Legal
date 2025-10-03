<?php

namespace App\Middleware;

use App\Core\Request;

class CorsMiddleware
{
    public function handle(Request $request)
    {
        // Set CORS headers
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, X-CSRF-TOKEN');
        header('Access-Control-Max-Age: 86400');
        
        // Handle preflight requests
        if ($request->getMethod() === 'OPTIONS') {
            http_response_code(200);
            exit;
        }
        
        return true;
    }
}
