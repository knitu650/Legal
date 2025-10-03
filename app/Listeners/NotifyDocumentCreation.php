<?php

namespace App\Listeners;

use App\Services\Notification\NotificationService;

class NotifyDocumentCreation
{
    protected $notificationService;
    
    public function __construct()
    {
        $this->notificationService = new NotificationService();
    }
    
    public function handle($event)
    {
        $document = $event->document;
        $user = $event->user;
        
        $this->notificationService->notifyDocumentCreated(
            $user->id, 
            $document->id, 
            $document->title
        );
    }
}
