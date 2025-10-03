<?php

namespace App\Services\Consultation;

use App\Models\Consultation;
use App\Services\Notification\NotificationService;

class BookingService
{
    protected $notificationService;
    
    public function __construct()
    {
        $this->notificationService = new NotificationService();
    }
    
    public function createBooking($userId, $lawyerId, $data)
    {
        $consultationModel = new Consultation();
        
        $consultation = $consultationModel->create([
            'user_id' => $userId,
            'lawyer_id' => $lawyerId,
            'type' => $data['type'],
            'scheduled_at' => $data['scheduled_at'],
            'duration_minutes' => $data['duration_minutes'] ?? 60,
            'status' => CONSULTATION_PENDING
        ]);
        
        // Notify lawyer
        $this->notificationService->notify(
            $lawyerId,
            'consultation_booked',
            'New Consultation Request',
            'You have a new consultation request',
            ['consultation_id' => $consultation->id],
            ['database', 'email']
        );
        
        return $consultation;
    }
    
    public function cancelBooking($consultationId, $reason = '')
    {
        $consultationModel = new Consultation();
        $consultation = $consultationModel->find($consultationId);
        
        if (!$consultation) {
            return false;
        }
        
        $consultation->cancel();
        
        // Send cancellation notifications
        
        return true;
    }
    
    public function rescheduleBooking($consultationId, $newScheduledAt)
    {
        $consultationModel = new Consultation();
        
        $consultationModel->update($consultationId, [
            'scheduled_at' => $newScheduledAt
        ]);
        
        // Send reschedule notifications
        
        return true;
    }
}
