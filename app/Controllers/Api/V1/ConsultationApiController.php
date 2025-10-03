<?php

namespace App\Controllers\Api\V1;

use App\Core\Controller;
use App\Models\Consultation;

class ConsultationApiController extends Controller
{
    public function index()
    {
        if (!$this->isAuthenticated()) {
            $this->json(['error' => 'Unauthenticated'], 401);
            return;
        }
        
        $user = $this->auth();
        $consultationModel = new Consultation();
        
        $consultations = $consultationModel->where('user_id', $user->id)
                                          ->orderBy('scheduled_at', 'DESC')
                                          ->get();
        
        $this->json([
            'success' => true,
            'data' => $consultations
        ]);
    }
    
    public function store()
    {
        if (!$this->isAuthenticated()) {
            $this->json(['error' => 'Unauthenticated'], 401);
            return;
        }
        
        $user = $this->auth();
        
        $data = [
            'user_id' => $user->id,
            'lawyer_id' => $this->request->input('lawyer_id'),
            'type' => $this->request->input('type'),
            'scheduled_at' => $this->request->input('scheduled_at'),
            'duration_minutes' => $this->request->input('duration_minutes', 60),
            'status' => CONSULTATION_PENDING
        ];
        
        $consultationModel = new Consultation();
        $consultation = $consultationModel->create($data);
        
        $this->json([
            'success' => true,
            'consultation' => $consultation
        ], 201);
    }
}
