<?php

namespace App\Services\Consultation;

use App\Services\Integration\ZoomService;

class VideoCallService
{
    protected $zoomService;
    
    public function __construct()
    {
        $this->zoomService = new ZoomService();
    }
    
    public function createMeeting($consultationId, $scheduledAt, $duration = 60)
    {
        $meeting = $this->zoomService->createMeeting([
            'topic' => 'Legal Consultation #' . $consultationId,
            'start_time' => $scheduledAt,
            'duration' => $duration,
            'timezone' => 'Asia/Kolkata'
        ]);
        
        return [
            'meeting_id' => $meeting['id'],
            'join_url' => $meeting['join_url'],
            'start_url' => $meeting['start_url'],
            'password' => $meeting['password']
        ];
    }
    
    public function getMeetingDetails($meetingId)
    {
        return $this->zoomService->getMeeting($meetingId);
    }
    
    public function cancelMeeting($meetingId)
    {
        return $this->zoomService->deleteMeeting($meetingId);
    }
}
