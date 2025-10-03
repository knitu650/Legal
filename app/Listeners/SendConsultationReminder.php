<?php

namespace App\Listeners;

use App\Services\Notification\EmailService;
use App\Services\Notification\SMSService;

class SendConsultationReminder
{
    protected $emailService;
    protected $smsService;
    
    public function __construct()
    {
        $this->emailService = new EmailService();
        $this->smsService = new SMSService();
    }
    
    public function handle($event)
    {
        $consultation = $event->consultation;
        
        // Send reminder email
        // Send reminder SMS
    }
}
