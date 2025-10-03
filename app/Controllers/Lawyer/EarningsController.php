<?php

namespace App\Controllers\Lawyer;

use App\Core\Controller;
use App\Models\Lawyer;
use App\Models\Consultation;

class EarningsController extends Controller
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
                                          ->where('status', CONSULTATION_COMPLETED)
                                          ->get();
        
        $earnings = [
            'total' => 0,
            'this_month' => 0,
            'pending_withdrawal' => 0,
            'withdrawn' => 0,
        ];
        
        foreach ($consultations as $consultation) {
            $earnings['total'] += $consultation->amount;
        }
        
        $this->view('lawyer/earnings/index', [
            'pageTitle' => 'Earnings',
            'earnings' => $earnings,
            'consultations' => $consultations
        ]);
    }
    
    public function withdraw()
    {
        $amount = $this->request->input('amount');
        
        // Process withdrawal request
        
        flash('success', 'Withdrawal request submitted!');
        $this->redirect('/lawyer/earnings');
    }
}
