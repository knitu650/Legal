<?php

namespace App\Utils;

class SMS
{
    protected $config;
    
    public function __construct()
    {
        $this->config = require __DIR__ . '/../../config/sms.php';
    }
    
    public function send($to, $message)
    {
        $driver = $this->config['driver'];
        
        switch ($driver) {
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
        // Use Twilio API
        return true;
    }
    
    protected function sendViaMsg91($to, $message)
    {
        // Use MSG91 API
        return true;
    }
}
