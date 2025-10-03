<?php

namespace App\Middleware;

use App\Core\Session;
use App\Core\Request;

class MisMiddleware
{
    public function handle(Request $request)
    {
        $user = Session::get('user');
        
        if (!$user || $user->role_id != ROLE_MIS) {
            header('Location: /');
            exit;
        }
        
        return true;
    }
}
