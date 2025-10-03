<?php

namespace App\Middleware;

use App\Core\Session;
use App\Core\Request;

class AdminMiddleware
{
    public function handle(Request $request)
    {
        $user = Session::get('user');
        
        if (!$user || !in_array($user->role_id, [ROLE_SUPER_ADMIN, ROLE_ADMIN])) {
            if ($request->isAjax()) {
                header('Content-Type: application/json');
                http_response_code(403);
                echo json_encode(['error' => 'Unauthorized']);
                exit;
            }
            
            header('Location: /');
            exit;
        }
        
        return true;
    }
}
