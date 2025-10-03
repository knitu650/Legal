<?php

namespace App\Events;

class DocumentCreated
{
    public $document;
    public $user;
    
    public function __construct($document, $user)
    {
        $this->document = $document;
        $this->user = $user;
    }
    
    public function dispatch()
    {
        $listeners = [
            new \App\Listeners\NotifyDocumentCreation()
        ];
        
        foreach ($listeners as $listener) {
            $listener->handle($this);
        }
    }
}
