<?php

namespace App\Services\Notification;

class SMSService
{
    protected $driver;
    protected $config;
    
    public function __construct()
    {
        $this->config = require __DIR__ . '/../../../config/sms.php';
        $this->driver = $this->config['driver'];
    }
    
    public function send($to, $message)
    {
        switch ($this->driver) {
            case 'twilio':
                return $this->sendViaTwilio($to, $message);
            case 'msg91':
                return $this->sendViaMsg91($to, $message);
            default:
                return false;
        }
    }
    
    protected function sendViaTwilio($to, $message)
    {
        $sid = $this->config['twilio']['sid'];
        $token = $this->config['twilio']['token'];
        $from = $this->config['twilio']['from'];
        
        // In production, use Twilio SDK
        // Mock implementation
        return true;
    }
    
    protected function sendViaMsg91($to, $message)
    {
        $authKey = $this->config['msg91']['auth_key'];
        $senderId = $this->config['msg91']['sender_id'];
        
        // In production, use MSG91 API
        // Mock implementation
        return true;
    }
    
    public function sendOTP($phone, $otp)
    {
        $message = "Your OTP is: $otp. Valid for 10 minutes. Do not share with anyone.";
        return $this->send($phone, $message);
    }
    
    public function sendNotification($phone, $text)
    {
        return $this->send($phone, $text);
    }
}
