<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Services\Notification\EmailService;
use App\Models\ActivityLog;

class RegisterService
{
    protected $emailService;
    
    public function __construct()
    {
        $this->emailService = new EmailService();
    }
    
    public function register($data)
    {
        // Check if email already exists
        $existing = User::findByEmail($data['email']);
        if ($existing) {
            return [
                'success' => false,
                'message' => 'Email already registered',
                'errors' => ['email' => ['Email is already taken']]
            ];
        }
        
        // Create user
        $userModel = new User();
        $user = $userModel->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => password_hash($data['password'], PASSWORD_BCRYPT),
            'role_id' => $data['role_id'] ?? ROLE_USER,
            'status' => 'active'
        ]);
        
        // Send welcome email
        $this->sendWelcomeEmail($user);
        
        // Log registration
        ActivityLog::log($user->id, 'user_registered', 'New user registered', [
            'email' => $user->email,
            'name' => $user->name
        ]);
        
        return [
            'success' => true,
            'user' => $user
        ];
    }
    
    protected function sendWelcomeEmail($user)
    {
        $this->emailService->sendTemplate($user->email, 'auth/welcome', [
            'subject' => 'Welcome to ' . config('app.name'),
            'name' => $user->name,
            'email' => $user->email
        ]);
    }
    
    public function verifyEmail($token)
    {
        // Implement email verification
        return true;
    }
}
