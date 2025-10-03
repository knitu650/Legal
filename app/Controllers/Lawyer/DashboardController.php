<?php

namespace App\Controllers\Lawyer;

use App\Core\Controller;
use App\Models\Lawyer;
use App\Models\Consultation;

class DashboardController extends Controller
{
    public function index()
    {
        $user = $this->auth();
        $lawyerModel = new Lawyer();
        
        $lawyer = $lawyerModel->where('user_id', $user->id)->first();
        
        if (!$lawyer) {
            flash('error', 'Lawyer profile not found.');
            $this->redirect('/');
            return;
        }
        
        $consultationModel = new Consultation();
        
        $stats = [
            'total_consultations' => $consultationModel->where('lawyer_id', $lawyer->id)->count(),
            'upcoming_consultations' => $consultationModel->where('lawyer_id', $lawyer->id)
                                                         ->where('status', CONSULTATION_CONFIRMED)
                                                         ->count(),
            'completed_consultations' => $consultationModel->where('lawyer_id', $lawyer->id)
                                                          ->where('status', CONSULTATION_COMPLETED)
                                                          ->count(),
            'total_earnings' => 0,
            'rating' => $lawyer->rating,
        ];
        
        $upcomingConsultations = $consultationModel->where('lawyer_id', $lawyer->id)
                                                   ->where('status', CONSULTATION_CONFIRMED)
                                                   ->orderBy('scheduled_at', 'ASC')
                                                   ->limit(5)
                                                   ->get();
        
        $this->view('lawyer/dashboard/index', [
            'pageTitle' => 'Lawyer Dashboard',
            'lawyer' => $lawyer,
            'stats' => $stats,
            'upcomingConsultations' => $upcomingConsultations
        ]);
    }
}
