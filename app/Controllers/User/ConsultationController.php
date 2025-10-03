<?php

namespace App\Controllers\User;

use App\Core\Controller;
use App\Models\Consultation;
use App\Models\Lawyer;

class ConsultationController extends Controller
{
    public function index()
    {
        $user = $this->auth();
        $consultationModel = new Consultation();
        
        $consultations = $consultationModel->where('user_id', $user->id)
                                          ->orderBy('scheduled_at', 'DESC')
                                          ->get();
        
        $this->view('user/consultation/index', [
            'pageTitle' => 'My Consultations',
            'consultations' => $consultations
        ]);
    }
    
    public function book()
    {
        $user = $this->auth();
        $lawyerId = $this->request->query('lawyer_id');
        
        $lawyerModel = new Lawyer();
        $lawyer = $lawyerModel->find($lawyerId);
        
        if (!$lawyer || !$lawyer->isVerified()) {
            flash('error', 'Lawyer not found.');
            $this->redirect('/consultation/lawyers');
            return;
        }
        
        $this->view('user/consultation/book', [
            'pageTitle' => 'Book Consultation',
            'lawyer' => $lawyer
        ]);
    }
    
    public function store()
    {
        $user = $this->auth();
        
        $data = [
            'lawyer_id' => $this->request->input('lawyer_id'),
            'type' => $this->request->input('type'),
            'scheduled_at' => $this->request->input('scheduled_at'),
            'duration_minutes' => $this->request->input('duration_minutes', 60),
        ];
        
        $isValid = $this->validate($data, [
            'lawyer_id' => 'required|numeric',
            'scheduled_at' => 'required',
        ]);
        
        if (!$isValid) {
            flash('error', 'Please fill all required fields.');
            $this->back();
            return;
        }
        
        $consultationModel = new Consultation();
        $consultation = $consultationModel->create([
            'user_id' => $user->id,
            'lawyer_id' => $data['lawyer_id'],
            'type' => $data['type'],
            'scheduled_at' => $data['scheduled_at'],
            'duration_minutes' => $data['duration_minutes'],
            'status' => CONSULTATION_PENDING
        ]);
        
        flash('success', 'Consultation booked successfully!');
        $this->redirect('/user/consultations/' . $consultation->id);
    }
    
    public function show($id)
    {
        $user = $this->auth();
        $consultationModel = new Consultation();
        $consultation = $consultationModel->find($id);
        
        if (!$consultation || $consultation->user_id != $user->id) {
            flash('error', 'Consultation not found.');
            $this->redirect('/user/consultations');
            return;
        }
        
        $this->view('user/consultation/view', [
            'pageTitle' => 'Consultation Details',
            'consultation' => $consultation
        ]);
    }
    
    public function history()
    {
        $user = $this->auth();
        $consultationModel = new Consultation();
        
        $consultations = $consultationModel->where('user_id', $user->id)
                                          ->where('status', CONSULTATION_COMPLETED)
                                          ->orderBy('scheduled_at', 'DESC')
                                          ->get();
        
        $this->view('user/consultation/history', [
            'pageTitle' => 'Consultation History',
            'consultations' => $consultations
        ]);
    }
}
