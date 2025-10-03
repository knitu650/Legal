<?php

namespace App\Jobs;

use App\Utils\SMS;

class SendSMSJob
{
    protected $to;
    protected $message;
    
    public function __construct($to, $message)
    {
        $this->to = $to;
        $this->message = $message;
    }
    
    public function handle()
    {
        $sms = new SMS();
        return $sms->send($this->to, $this->message);
    }
    
    public function dispatch()
    {
        return $this->handle();
    }
}
