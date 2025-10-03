<?php

namespace App\Events;

class UserRegistered
{
    public $user;
    
    public function __construct($user)
    {
        $this->user = $user;
    }
    
    public function getUser()
    {
        return $this->user;
    }
    
    public function dispatch()
    {
        // Trigger event listeners
        $listeners = [
            new \App\Listeners\SendWelcomeEmail(),
            new \App\Listeners\UpdateAnalytics()
        ];
        
        foreach ($listeners as $listener) {
            $listener->handle($this);
        }
    }
}
