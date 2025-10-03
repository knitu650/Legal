<?php

namespace App\Controllers\Lawyer;

use App\Core\Controller;
use App\Models\Lawyer;
use App\Models\Consultation;

class ClientController extends Controller
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
        
        // Get unique clients from consultations
        $consultationModel = new Consultation();
        $consultations = $consultationModel->where('lawyer_id', $lawyer->id)->get();
        
        $clientIds = [];
        foreach ($consultations as $consultation) {
            if (!in_array($consultation->user_id, $clientIds)) {
                $clientIds[] = $consultation->user_id;
            }
        }
        
        $this->view('lawyer/clients/index', [
            'pageTitle' => 'Clients',
            'clientIds' => $clientIds
        ]);
    }
    
    public function show($id)
    {
        $user = $this->auth();
        $lawyerModel = new Lawyer();
        $lawyer = $lawyerModel->where('user_id', $user->id)->first();
        
        // Get client's consultation history with this lawyer
        $consultationModel = new Consultation();
        $consultations = $consultationModel->where('lawyer_id', $lawyer->id)
                                          ->where('user_id', $id)
                                          ->orderBy('scheduled_at', 'DESC')
                                          ->get();
        
        $this->view('lawyer/clients/view', [
            'pageTitle' => 'Client Details',
            'clientId' => $id,
            'consultations' => $consultations
        ]);
    }
}
