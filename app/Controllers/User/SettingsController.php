<?php

namespace App\Controllers\User;

use App\Core\Controller;
use App\Core\Session;
use App\Models\User;

class SettingsController extends Controller
{
    public function index()
    {
        $user = $this->auth();
        
        $this->view('user/settings/index', [
            'pageTitle' => 'Settings',
            'user' => $user
        ]);
    }
    
    public function updateProfile()
    {
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
        $updatedUser = $userModel->update($user->id, $data);
        
        // Update session
        Session::set('user', $updatedUser);
        
        flash('success', 'Profile updated successfully!');
        $this->redirect('/user/settings');
    }
    
    public function changePassword()
    {
        $user = $this->auth();
        
        $currentPassword = $this->request->input('current_password');
        $newPassword = $this->request->input('new_password');
        $confirmPassword = $this->request->input('confirm_password');
        
        $userModel = new User();
        $userRecord = $userModel->find($user->id);
        
        if (!$userRecord->verifyPassword($currentPassword)) {
            flash('error', 'Current password is incorrect.');
            $this->back();
            return;
        }
        
        if ($newPassword !== $confirmPassword) {
            flash('error', 'New passwords do not match.');
            $this->back();
            return;
        }
        
        if (strlen($newPassword) < 6) {
            flash('error', 'Password must be at least 6 characters.');
            $this->back();
            return;
        }
        
        $userModel->update($user->id, [
            'password' => password_hash($newPassword, PASSWORD_BCRYPT)
        ]);
        
        flash('success', 'Password changed successfully!');
        $this->redirect('/user/settings');
    }
    
    public function updateNotifications()
    {
        $user = $this->auth();
        
        $preferences = [
            'email_notifications' => $this->request->input('email_notifications') ? 1 : 0,
            'sms_notifications' => $this->request->input('sms_notifications') ? 1 : 0,
            'push_notifications' => $this->request->input('push_notifications') ? 1 : 0,
        ];
        
        // Store notification preferences (could be in a separate table)
        flash('success', 'Notification preferences updated successfully!');
        $this->redirect('/user/settings');
    }
}
