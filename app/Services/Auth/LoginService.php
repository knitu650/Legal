<?php

namespace App\Services\Auth;

use App\Core\Session;
use App\Models\User;
use App\Models\ActivityLog;

class LoginService
{
    public function attempt($email, $password, $remember = false)
    {
        $user = User::findByEmail($email);
        
        if (!$user) {
            $this->logFailedAttempt($email);
            return [
                'success' => false,
                'message' => 'Invalid credentials'
            ];
        }
        
        if (!$user->verifyPassword($password)) {
            $this->logFailedAttempt($email, $user->id);
            return [
                'success' => false,
                'message' => 'Invalid credentials'
            ];
        }
        
        if ($user->status !== 'active') {
            return [
                'success' => false,
                'message' => 'Account is not active'
            ];
        }
        
        // Set session
        Session::set('user', $user);
        Session::regenerate();
        
        // Handle remember me
        if ($remember) {
            $this->setRememberToken($user);
        }
        
        // Log successful login
        ActivityLog::log($user->id, 'user_login', 'User logged in successfully', [
            'ip' => $_SERVER['REMOTE_ADDR'] ?? null,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null
        ]);
        
        return [
            'success' => true,
            'user' => $user
        ];
    }
    
    protected function logFailedAttempt($email, $userId = null)
    {
        ActivityLog::log($userId, 'login_failed', "Failed login attempt for email: $email", [
            'email' => $email,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? null
        ]);
    }
    
    protected function setRememberToken($user)
    {
        $token = bin2hex(random_bytes(32));
        
        // Store token in database
        $userModel = new User();
        $userModel->update($user->id, [
            'remember_token' => hash('sha256', $token)
        ]);
        
        // Set cookie (30 days)
        setcookie('remember_token', $token, time() + (30 * 24 * 60 * 60), '/', '', false, true);
    }
    
    public function checkRememberToken()
    {
        if (isset($_COOKIE['remember_token'])) {
            $token = $_COOKIE['remember_token'];
            $hashedToken = hash('sha256', $token);
            
            $userModel = new User();
            $users = $userModel->all();
            
            foreach ($users as $user) {
                if ($user->remember_token === $hashedToken) {
                    Session::set('user', $user);
                    return $user;
                }
            }
        }
        
        return null;
    }
}
