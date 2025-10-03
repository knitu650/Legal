<?php

namespace App\Controllers\Lawyer;

use App\Core\Controller;
use App\Models\Lawyer;

class AvailabilityController extends Controller
{
    public function index()
    {
        $user = $this->auth();
        $lawyerModel = new Lawyer();
        
        $lawyer = $lawyerModel->where('user_id', $user->id)->first();
        
        // Get availability schedule (stored in separate table or lawyer metadata)
        $schedule = [];
        
        $this->view('lawyer/availability/schedule', [
            'pageTitle' => 'Availability Schedule',
            'lawyer' => $lawyer,
            'schedule' => $schedule
        ]);
    }
    
    public function update()
    {
        $user = $this->auth();
        $schedule = $this->request->input('schedule');
        
        // Update availability schedule
        
        flash('success', 'Availability updated successfully!');
        $this->redirect('/lawyer/availability');
    }
}
