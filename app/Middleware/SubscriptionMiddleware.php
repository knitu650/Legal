<?php

namespace App\Middleware;

use App\Core\Request;
use App\Core\Session;
use App\Models\Subscription;

class SubscriptionMiddleware
{
    public function handle(Request $request)
    {
        $user = Session::get('user');
        
        if (!$user) {
            return true; // Let AuthMiddleware handle authentication
        }
        
        $subscriptionModel = new Subscription();
        $activeSubscription = $subscriptionModel->where('user_id', $user->id)
                                               ->where('status', SUBSCRIPTION_ACTIVE)
                                               ->first();
        
        if (!$activeSubscription || $activeSubscription->isExpired()) {
            // User doesn't have active subscription
            // Redirect to subscription page or show limited access
            
            if ($request->isAjax()) {
                header('Content-Type: application/json');
                http_response_code(403);
                echo json_encode([
                    'error' => 'Subscription required',
                    'message' => 'Please subscribe to access this feature'
                ]);
                exit;
            }
            
            Session::set('intended_url', $request->getUri());
            header('Location: /user/subscription/plans');
            exit;
        }
        
        return true;
    }
}
