<?php

namespace App\Middleware;

use App\Core\Request;
use App\Core\Session;

class RateLimitMiddleware
{
    protected $maxAttempts = 60; // requests per minute
    protected $decayMinutes = 1;
    
    public function handle(Request $request)
    {
        $key = $this->getRateLimitKey($request);
        
        $attempts = Session::get($key, []);
        $now = time();
        
        // Clean old attempts
        $attempts = array_filter($attempts, function($timestamp) use ($now) {
            return $timestamp > $now - ($this->decayMinutes * 60);
        });
        
        if (count($attempts) >= $this->maxAttempts) {
            http_response_code(429);
            header('Content-Type: application/json');
            echo json_encode([
                'error' => 'Too many requests',
                'message' => 'Rate limit exceeded. Please try again later.'
            ]);
            exit;
        }
        
        $attempts[] = $now;
        Session::set($key, $attempts);
        
        // Set rate limit headers
        header('X-RateLimit-Limit: ' . $this->maxAttempts);
        header('X-RateLimit-Remaining: ' . ($this->maxAttempts - count($attempts)));
        header('X-RateLimit-Reset: ' . ($now + ($this->decayMinutes * 60)));
        
        return true;
    }
    
    protected function getRateLimitKey(Request $request)
    {
        $ip = $request->ip();
        $uri = $request->getUri();
        
        return 'rate_limit_' . md5($ip . $uri);
    }
}
