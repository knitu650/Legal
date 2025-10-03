<?php

namespace App\Controllers\Web;

use App\Core\Controller;
use App\Models\User;

class ProfileController extends Controller
{
    public function index()
    {
        if (!$this->isAuthenticated()) {
            $this->redirect('/login');
            return;
        }
        
        $user = $this->auth();
        
        $this->view('web/profile/index', [
            'pageTitle' => 'My Profile',
            'user' => $user
        ]);
    }
    
    public function update()
    {
        if (!$this->isAuthenticated()) {
            $this->redirect('/login');
            return;
        }
        
        $user = $this->auth();
        
        $data = [
            'name' => $this->request->input('name'),
            'phone' => $this->request->input('phone'),
        ];
        
        $isValid = $this->validate($data, [
            'name' => 'required|min:3',
            'phone' => 'required|min:10',
        ]);
        
        if (!$isValid) {
            flash('error', 'Please fill all fields correctly.');
            $this->back();
            return;
        }
        
        $userModel = new User();
        $userModel->update($user->id, $data);
        
        flash('success', 'Profile updated successfully!');
        $this->redirect('/profile');
    }
}
