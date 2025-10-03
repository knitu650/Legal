<?php

namespace App\Events;

class ConsultationBooked
{
    public $consultation;
    
    public function __construct($consultation)
    {
        $this->consultation = $consultation;
    }
    
    public function dispatch()
    {
        $listeners = [
            new \App\Listeners\SendConsultationReminder()
        ];
        
        foreach ($listeners as $listener) {
            $listener->handle($this);
        }
    }
}
