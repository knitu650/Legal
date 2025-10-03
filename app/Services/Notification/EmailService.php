<?php

namespace App\Services\Notification;

class EmailService
{
    protected $config;
    
    public function __construct()
    {
        $this->config = require __DIR__ . '/../../../config/mail.php';
    }
    
    public function send($to, $subject, $body, $from = null)
    {
        $from = $from ?? $this->config['from']['address'];
        $fromName = $this->config['from']['name'];
        
        // Email headers
        $headers = [
            'From: ' . $fromName . ' <' . $from . '>',
            'Reply-To: ' . $from,
            'X-Mailer: PHP/' . phpversion(),
            'MIME-Version: 1.0',
            'Content-Type: text/html; charset=UTF-8'
        ];
        
        // In production, use PHPMailer or similar library
        // For now, using PHP's mail() function
        $success = mail($to, $subject, $body, implode("\r\n", $headers));
        
        return $success;
    }
    
    public function sendTemplate($to, $template, $data = [])
    {
        $templatePath = __DIR__ . '/../../../resources/views/emails/' . $template . '.php';
        
        if (!file_exists($templatePath)) {
            return false;
        }
        
        // Extract data for template
        extract($data);
        
        // Capture template output
        ob_start();
        include $templatePath;
        $body = ob_get_clean();
        
        $subject = $data['subject'] ?? 'Notification from ' . config('app.name');
        
        return $this->send($to, $subject, $body);
    }
    
    public function sendWelcomeEmail($user)
    {
        return $this->sendTemplate($user->email, 'auth/welcome', [
            'subject' => 'Welcome to ' . config('app.name'),
            'name' => $user->name
        ]);
    }
    
    public function sendPasswordResetEmail($user, $token)
    {
        $resetUrl = url('/reset-password/' . $token);
        
        return $this->sendTemplate($user->email, 'auth/password-reset', [
            'subject' => 'Password Reset Request',
            'name' => $user->name,
            'reset_url' => $resetUrl
        ]);
    }
}
