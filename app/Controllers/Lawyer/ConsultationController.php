<?php

namespace App\Controllers\Lawyer;

use App\Core\Controller;
use App\Models\Lawyer;
use App\Models\Consultation;

class ConsultationController extends Controller
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
        $consultations = $consultationModel->where('lawyer_id', $lawyer->id)
                                          ->orderBy('scheduled_at', 'DESC')
                                          ->get();
        
        $this->view('lawyer/consultations/index', [
            'pageTitle' => 'Consultations',
            'consultations' => $consultations
        ]);
    }
    
    public function show($id)
    {
        $user = $this->auth();
        $lawyerModel = new Lawyer();
        $lawyer = $lawyerModel->where('user_id', $user->id)->first();
        
        $consultationModel = new Consultation();
        $consultation = $consultationModel->find($id);
        
        if (!$consultation || $consultation->lawyer_id != $lawyer->id) {
            flash('error', 'Consultation not found.');
            $this->redirect('/lawyer/consultations');
            return;
        }
        
        $this->view('lawyer/consultations/view', [
            'pageTitle' => 'Consultation Details',
            'consultation' => $consultation
        ]);
    }
    
    public function complete($id)
    {
        $user = $this->auth();
        $lawyerModel = new Lawyer();
        $lawyer = $lawyerModel->where('user_id', $user->id)->first();
        
        $consultationModel = new Consultation();
        $consultation = $consultationModel->find($id);
        
        if (!$consultation || $consultation->lawyer_id != $lawyer->id) {
            flash('error', 'Consultation not found.');
            $this->redirect('/lawyer/consultations');
            return;
        }
        
        $notes = $this->request->input('notes', '');
        $consultation->complete($notes);
        
        flash('success', 'Consultation marked as completed!');
        $this->redirect('/lawyer/consultations/' . $id);
    }
}
