<?php
namespace App\Middleware;

use App\Core\Request;
use App\Services\Auth;

class AuthMiddleware {
    public function handle(Request $request, callable $next) {
        if (Auth::getInstance()->guest()) {
            return redirect('/login');
        }
        
        // Check session timeout (30 minutes)
        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
            Auth::getInstance()->logout();
            return redirect('/login')->with('error', 'Session expired. Please login again.');
        }
        
        $_SESSION['last_activity'] = time();
        return $next($request);
    }
}