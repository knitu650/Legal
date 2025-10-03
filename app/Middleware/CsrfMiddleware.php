<?php

namespace App\Middleware;

use App\Core\Request;
use App\Core\Session;

class CsrfMiddleware
{
    public function handle(Request $request)
    {
        // Only check CSRF for state-changing methods
        if (!in_array($request->getMethod(), ['POST', 'PUT', 'DELETE'])) {
            return true;
        }
        
        // Skip CSRF check for API routes
        if (strpos($request->getUri(), '/api/') === 0) {
            return true;
        }
        
        $token = $request->input('_csrf_token') ?? $request->getHeader('X-CSRF-TOKEN');
        $sessionToken = Session::get('_csrf_token');
        
        if (!$token || !$sessionToken || !hash_equals($sessionToken, $token)) {
            http_response_code(419);
            die('CSRF token mismatch');
        }
        
        return true;
    }
}
