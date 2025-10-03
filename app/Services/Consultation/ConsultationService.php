<?php

namespace App\Services\Consultation;

use App\Models\Consultation;
use App\Models\Lawyer;

class ConsultationService
{
    public function bookConsultation($userId, $lawyerId, $scheduledAt, $type = 'video', $duration = 60)
    {
        $lawyerModel = new Lawyer();
        $lawyer = $lawyerModel->find($lawyerId);
        
        if (!$lawyer || !$lawyer->isVerified()) {
            return [
                'success' => false,
                'message' => 'Lawyer not available'
            ];
        }
        
        // Check lawyer availability
        $isAvailable = $this->checkAvailability($lawyerId, $scheduledAt, $duration);
        
        if (!$isAvailable) {
            return [
                'success' => false,
                'message' => 'Lawyer is not available at this time'
            ];
        }
        
        // Calculate amount
        $amount = ($lawyer->hourly_rate / 60) * $duration;
        
        // Create consultation
        $consultationModel = new Consultation();
        $consultation = $consultationModel->create([
            'user_id' => $userId,
            'lawyer_id' => $lawyerId,
            'type' => $type,
            'scheduled_at' => $scheduledAt,
            'duration_minutes' => $duration,
            'amount' => $amount,
            'status' => CONSULTATION_PENDING
        ]);
        
        return [
            'success' => true,
            'consultation' => $consultation
        ];
    }
    
    public function confirmConsultation($consultationId, $meetingLink = null)
    {
        $consultationModel = new Consultation();
        $consultation = $consultationModel->find($consultationId);
        
        if (!$consultation) {
            return false;
        }
        
        $consultationModel->update($consultationId, [
            'status' => CONSULTATION_CONFIRMED,
            'meeting_link' => $meetingLink
        ]);
        
        // Send confirmation email/SMS
        
        return true;
    }
    
    public function completeConsultation($consultationId, $notes = '')
    {
        $consultationModel = new Consultation();
        $consultation = $consultationModel->find($consultationId);
        
        if (!$consultation) {
            return false;
        }
        
        $consultation->complete($notes);
        
        // Update lawyer statistics
        $lawyerModel = new Lawyer();
        $lawyer = $lawyerModel->find($consultation->lawyer_id);
        
        if ($lawyer) {
            $lawyerModel->update($lawyer->id, [
                'total_consultations' => $lawyer->total_consultations + 1
            ]);
        }
        
        return true;
    }
    
    protected function checkAvailability($lawyerId, $scheduledAt, $duration)
    {
        $consultationModel = new Consultation();
        
        $scheduledTime = strtotime($scheduledAt);
        $endTime = $scheduledTime + ($duration * 60);
        
        $conflictingConsultations = $consultationModel
            ->where('lawyer_id', $lawyerId)
            ->where('status', CONSULTATION_CONFIRMED)
            ->get();
        
        foreach ($conflictingConsultations as $existing) {
            $existingStart = strtotime($existing->scheduled_at);
            $existingEnd = $existingStart + ($existing->duration_minutes * 60);
            
            // Check for overlap
            if (($scheduledTime >= $existingStart && $scheduledTime < $existingEnd) ||
                ($endTime > $existingStart && $endTime <= $existingEnd)) {
                return false;
            }
        }
        
        return true;
    }
}
