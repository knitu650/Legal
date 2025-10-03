<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Services\Notification\EmailService;

class PasswordResetService
{
    protected $emailService;
    
    public function __construct()
    {
        $this->emailService = new EmailService();
    }
    
    public function sendResetLink($email)
    {
        $user = User::findByEmail($email);
        
        if (!$user) {
            return [
                'success' => false,
                'message' => 'User not found'
            ];
        }
        
        // Generate reset token
        $token = bin2hex(random_bytes(32));
        $hashedToken = hash('sha256', $token);
        
        // Store token (in production, use a password_resets table)
        $userModel = new User();
        $userModel->update($user->id, [
            'remember_token' => $hashedToken // Temporary storage
        ]);
        
        // Send email
        $this->emailService->sendPasswordResetEmail($user, $token);
        
        return [
            'success' => true,
            'message' => 'Password reset link sent'
        ];
    }
    
    public function resetPassword($token, $password)
    {
        $hashedToken = hash('sha256', $token);
        
        // Find user by token
        $userModel = new User();
        $users = $userModel->all();
        
        foreach ($users as $user) {
            if ($user->remember_token === $hashedToken) {
                // Update password
                $userModel->update($user->id, [
                    'password' => password_hash($password, PASSWORD_BCRYPT),
                    'remember_token' => null
                ]);
                
                return [
                    'success' => true,
                    'message' => 'Password reset successful'
                ];
            }
        }
        
        return [
            'success' => false,
            'message' => 'Invalid or expired token'
        ];
    }
}
