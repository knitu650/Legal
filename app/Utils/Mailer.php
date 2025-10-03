<?php

namespace App\Utils;

class Mailer
{
    protected $config;
    
    public function __construct()
    {
        $this->config = require __DIR__ . '/../../config/mail.php';
    }
    
    public function send($to, $subject, $body, $from = null)
    {
        $from = $from ?? $this->config['from']['address'];
        $fromName = $this->config['from']['name'];
        
        $headers = [
            'From: ' . $fromName . ' <' . $from . '>',
            'Reply-To: ' . $from,
            'MIME-Version: 1.0',
            'Content-Type: text/html; charset=UTF-8'
        ];
        
        return mail($to, $subject, $body, implode("\r\n", $headers));
    }
    
    public function queue($to, $subject, $body)
    {
        // Queue email for later sending
        // In production, use job queue
        
        return $this->send($to, $subject, $body);
    }
}
