<?php

namespace App\Listeners;

use App\Services\Notification\EmailService;

class SendWelcomeEmail
{
    protected $emailService;
    
    public function __construct()
    {
        $this->emailService = new EmailService();
    }
    
    public function handle($event)
    {
        $user = $event->getUser();
        
        $this->emailService->sendTemplate($user->email, 'auth/welcome', [
            'subject' => 'Welcome to ' . config('app.name'),
            'name' => $user->name
        ]);
    }
}
