<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Core\Session;

class SocialAuthService
{
    public function handleGoogleCallback($userData)
    {
        // Find or create user from Google data
        $user = User::findByEmail($userData['email']);
        
        if (!$user) {
            $userModel = new User();
            $user = $userModel->create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => password_hash(bin2hex(random_bytes(16)), PASSWORD_BCRYPT),
                'role_id' => ROLE_USER,
                'status' => 'active',
                'email_verified_at' => date('Y-m-d H:i:s')
            ]);
        }
        
        Session::set('user', $user);
        Session::regenerate();
        
        return $user;
    }
    
    public function handleFacebookCallback($userData)
    {
        // Similar to Google
        return $this->handleGoogleCallback($userData);
    }
    
    public function getGoogleAuthUrl()
    {
        $clientId = env('GOOGLE_CLIENT_ID');
        $redirectUri = url('/auth/google/callback');
        
        return "https://accounts.google.com/o/oauth2/v2/auth?" . http_build_query([
            'client_id' => $clientId,
            'redirect_uri' => $redirectUri,
            'response_type' => 'code',
            'scope' => 'email profile',
        ]);
    }
}
