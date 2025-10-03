<?php

namespace App\Middleware;

use App\Core\Session;
use App\Core\Request;

class AuthMiddleware
{
    public function handle(Request $request)
    {
        if (!Session::has('user')) {
            if ($request->isAjax()) {
                header('Content-Type: application/json');
                http_response_code(401);
                echo json_encode(['error' => 'Unauthenticated']);
                exit;
            }
            
            Session::set('intended_url', $request->getUri());
            header('Location: /login');
            exit;
        }
        
        return true;
    }
}
