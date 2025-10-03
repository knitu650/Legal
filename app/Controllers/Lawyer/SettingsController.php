<?php

namespace App\Controllers\Lawyer;

use App\Core\Controller;
use App\Models\Lawyer;

class SettingsController extends Controller
{
    public function index()
    {
        $user = $this->auth();
        $lawyerModel = new Lawyer();
        
        $lawyer = $lawyerModel->where('user_id', $user->id)->first();
        
        $this->view('lawyer/settings/profile', [
            'pageTitle' => 'Settings',
            'lawyer' => $lawyer
        ]);
    }
    
    public function update()
    {
        $user = $this->auth();
        
        $data = [
            'hourly_rate' => $this->request->input('hourly_rate'),
        ];
        
        $lawyerModel = new Lawyer();
        $lawyer = $lawyerModel->where('user_id', $user->id)->first();
        
        if ($lawyer) {
            $lawyerModel->update($lawyer->id, $data);
        }
        
        flash('success', 'Settings updated successfully!');
        $this->redirect('/lawyer/settings');
    }
}
