<?php

namespace App\Controllers\Lawyer;

use App\Core\Controller;
use App\Models\Lawyer;

class ProfileController extends Controller
{
    public function index()
    {
        $user = $this->auth();
        $lawyerModel = new Lawyer();
        
        $lawyer = $lawyerModel->where('user_id', $user->id)->first();
        
        $this->view('lawyer/profile/view', [
            'pageTitle' => 'My Profile',
            'lawyer' => $lawyer
        ]);
    }
    
    public function update()
    {
        $user = $this->auth();
        
        $data = [
            'specialization' => $this->request->input('specialization'),
            'experience_years' => $this->request->input('experience_years'),
            'education' => $this->request->input('education'),
            'bar_council_number' => $this->request->input('bar_council_number'),
            'hourly_rate' => $this->request->input('hourly_rate'),
            'bio' => $this->request->input('bio'),
            'languages' => json_encode($this->request->input('languages', [])),
        ];
        
        $lawyerModel = new Lawyer();
        $lawyer = $lawyerModel->where('user_id', $user->id)->first();
        
        if ($lawyer) {
            $lawyerModel->update($lawyer->id, $data);
        } else {
            $data['user_id'] = $user->id;
            $data['verification_status'] = 'pending';
            $lawyerModel->create($data);
        }
        
        flash('success', 'Profile updated successfully!');
        $this->redirect('/lawyer/profile');
    }
}
