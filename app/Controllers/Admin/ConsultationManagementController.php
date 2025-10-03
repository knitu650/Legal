<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Consultation;

class ConsultationManagementController extends Controller
{
    public function index()
    {
        $consultationModel = new Consultation();
        $status = $this->request->query('status');
        
        $query = $consultationModel;
        
        if ($status) {
            $query = $query->where('status', $status);
        }
        
        $consultations = $query->orderBy('scheduled_at', 'DESC')->get();
        
        $this->view('admin/consultations/index', [
            'pageTitle' => 'Consultation Management',
            'consultations' => $consultations
        ]);
    }
    
    public function show($id)
    {
        $consultationModel = new Consultation();
        $consultation = $consultationModel->find($id);
        
        if (!$consultation) {
            flash('error', 'Consultation not found.');
            $this->redirect('/admin/consultations');
            return;
        }
        
        $this->view('admin/consultations/show', [
            'pageTitle' => 'Consultation Details',
            'consultation' => $consultation
        ]);
    }
}
