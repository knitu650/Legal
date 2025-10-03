<?php

namespace App\Jobs;

use App\Utils\Mailer;

class SendEmailJob
{
    protected $to;
    protected $subject;
    protected $body;
    
    public function __construct($to, $subject, $body)
    {
        $this->to = $to;
        $this->subject = $subject;
        $this->body = $body;
    }
    
    public function handle()
    {
        $mailer = new Mailer();
        return $mailer->send($this->to, $this->subject, $this->body);
    }
    
    public function dispatch()
    {
        // In production, queue this job
        // For now, execute immediately
        return $this->handle();
    }
}
