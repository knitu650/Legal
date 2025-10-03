<?php

namespace App\Controllers\Lawyer;

use App\Core\Controller;
use App\Models\Lawyer;
use App\Models\Consultation;

class ScheduleController extends Controller
{
    public function index()
    {
        $user = $this->auth();
        $lawyerModel = new Lawyer();
        $lawyer = $lawyerModel->where('user_id', $user->id)->first();
        
        if (!$lawyer) {
            flash('error', 'Lawyer profile not found.');
            $this->redirect('/lawyer/dashboard');
            return;
        }
        
        $consultationModel = new Consultation();
        $upcomingConsultations = $consultationModel->where('lawyer_id', $lawyer->id)
                                                   ->where('status', CONSULTATION_CONFIRMED)
                                                   ->orderBy('scheduled_at', 'ASC')
                                                   ->get();
        
        $this->view('lawyer/schedule/index', [
            'pageTitle' => 'Schedule',
            'consultations' => $upcomingConsultations
        ]);
    }
}
