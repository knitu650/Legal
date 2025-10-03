<?php

namespace App\Services\Auth;

use App\Core\Session;
use App\Models\User;

class AuthService
{
    public function login($email, $password, $remember = false)
    {
        $user = User::findByEmail($email);
        
        if (!$user || !$user->verifyPassword($password)) {
            return [
                'success' => false,
                'message' => 'Invalid credentials'
            ];
        }
        
        if ($user->status !== 'active') {
            return [
                'success' => false,
                'message' => 'Account is inactive'
            ];
        }
        
        // Set session
        Session::set('user', $user);
        Session::regenerate();
        
        // Handle remember me
        if ($remember) {
            $token = bin2hex(random_bytes(32));
            // Store remember token in database
            // Set cookie
        }
        
        return [
            'success' => true,
            'user' => $user
        ];
    }
    
    public function register($data)
    {
        $userModel = new User();
        
        // Check if email already exists
        $existing = User::findByEmail($data['email']);
        if ($existing) {
            return [
                'success' => false,
                'message' => 'Email already registered'
            ];
        }
        
        // Create user
        $user = $userModel->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => password_hash($data['password'], PASSWORD_BCRYPT),
            'role_id' => ROLE_USER,
            'status' => 'active'
        ]);
        
        // Auto login
        Session::set('user', $user);
        Session::regenerate();
        
        // Send welcome email
        $emailService = new \App\Services\Notification\EmailService();
        $emailService->sendWelcomeEmail($user);
        
        return [
            'success' => true,
            'user' => $user
        ];
    }
    
    public function logout()
    {
        Session::remove('user');
        Session::regenerate();
        
        return ['success' => true];
    }
    
    public function getCurrentUser()
    {
        return Session::get('user');
    }
    
    public function isAuthenticated()
    {
        return Session::has('user');
    }
    
    public function hasRole($roleId)
    {
        $user = $this->getCurrentUser();
        return $user && $user->role_id == $roleId;
    }
}
