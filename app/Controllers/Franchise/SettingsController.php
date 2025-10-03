<?php

namespace App\Controllers\Franchise;

use App\Core\Controller;
use App\Models\Franchise;

class SettingsController extends Controller
{
    public function index()
    {
        $user = $this->auth();
        $franchiseModel = new Franchise();
        
        $franchise = $franchiseModel->where('user_id', $user->id)->first();
        
        $this->view('franchise/settings/profile', [
            'pageTitle' => 'Settings',
            'franchise' => $franchise
        ]);
    }
    
    public function update()
    {
        $user = $this->auth();
        
        $data = [
            'business_name' => $this->request->input('business_name'),
        ];
        
        $franchiseModel = new Franchise();
        $franchise = $franchiseModel->where('user_id', $user->id)->first();
        
        if ($franchise) {
            $franchiseModel->update($franchise->id, $data);
        }
        
        flash('success', 'Settings updated successfully!');
        $this->redirect('/franchise/settings');
    }
}
