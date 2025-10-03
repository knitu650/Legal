<?php

namespace App\Services\Consultation;

use App\Models\Consultation;

class AvailabilityService
{
    public function getAvailableSlots($lawyerId, $date)
    {
        $consultationModel = new Consultation();
        
        // Get lawyer's working hours (9 AM - 6 PM)
        $workingHours = [
            'start' => 9,
            'end' => 18
        ];
        
        // Get booked slots
        $bookedSlots = $consultationModel->where('lawyer_id', $lawyerId)
                                        ->where('status', CONSULTATION_CONFIRMED)
                                        ->get();
        
        $availableSlots = [];
        
        for ($hour = $workingHours['start']; $hour < $workingHours['end']; $hour++) {
            $slotTime = $date . ' ' . str_pad($hour, 2, '0', STR_PAD_LEFT) . ':00:00';
            
            if (!$this->isSlotBooked($slotTime, $bookedSlots)) {
                $availableSlots[] = [
                    'time' => $slotTime,
                    'formatted' => date('h:i A', strtotime($slotTime))
                ];
            }
        }
        
        return $availableSlots;
    }
    
    protected function isSlotBooked($slotTime, $bookedSlots)
    {
        $slotTimestamp = strtotime($slotTime);
        
        foreach ($bookedSlots as $booking) {
            $bookingStart = strtotime($booking->scheduled_at);
            $bookingEnd = $bookingStart + ($booking->duration_minutes * 60);
            
            if ($slotTimestamp >= $bookingStart && $slotTimestamp < $bookingEnd) {
                return true;
            }
        }
        
        return false;
    }
    
    public function setAvailability($lawyerId, $schedule)
    {
        // Store lawyer's availability schedule
        // schedule format: [{'day': 'monday', 'start': '09:00', 'end': '18:00'}]
        
        return true;
    }
}
