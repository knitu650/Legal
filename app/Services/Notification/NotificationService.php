<?php

namespace App\Services\Notification;

use App\Models\Notification;

class NotificationService
{
    protected $emailService;
    protected $smsService;
    
    public function __construct()
    {
        $this->emailService = new EmailService();
        $this->smsService = new SMSService();
    }
    
    public function notify($userId, $type, $title, $message, $data = [], $channels = ['database'])
    {
        $results = [];
        
        // Store in database
        if (in_array('database', $channels)) {
            $notification = Notification::send($userId, $type, $title, $message, $data);
            $results['database'] = $notification ? true : false;
        }
        
        // Send email
        if (in_array('email', $channels)) {
            // Get user email and send
            $results['email'] = true; // Mock
        }
        
        // Send SMS
        if (in_array('sms', $channels)) {
            // Get user phone and send
            $results['sms'] = true; // Mock
        }
        
        return $results;
    }
    
    public function notifyDocumentCreated($userId, $documentId, $documentTitle)
    {
        return $this->notify(
            $userId,
            'document_created',
            'Document Created',
            "Your document '$documentTitle' has been created successfully.",
            ['document_id' => $documentId],
            ['database', 'email']
        );
    }
    
    public function notifyPaymentCompleted($userId, $amount, $transactionId)
    {
        return $this->notify(
            $userId,
            'payment_completed',
            'Payment Successful',
            "Your payment of " . currency($amount) . " has been processed successfully.",
            ['transaction_id' => $transactionId],
            ['database', 'email', 'sms']
        );
    }
    
    public function notifyConsultationBooked($userId, $consultationId, $scheduledAt)
    {
        return $this->notify(
            $userId,
            'consultation_booked',
            'Consultation Booked',
            "Your consultation has been scheduled for $scheduledAt.",
            ['consultation_id' => $consultationId],
            ['database', 'email', 'sms']
        );
    }
}
