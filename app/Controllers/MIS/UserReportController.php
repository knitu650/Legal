<?php

namespace App\Controllers\MIS;

use App\Core\Controller;
use App\Models\User;

class UserReportController extends Controller
{
    public function index()
    {
        $userModel = new User();
        
        $report = [
            'total_users' => $userModel->count(),
            'active_users' => $userModel->where('status', 'active')->count(),
            'inactive_users' => $userModel->where('status', 'inactive')->count(),
            'suspended_users' => $userModel->where('status', 'suspended')->count(),
            'by_role' => $this->getUsersByRole(),
            'registration_trend' => $this->getRegistrationTrend(),
            'top_users' => $this->getTopUsers(),
        ];
        
        $this->view('mis/reports/users', [
            'pageTitle' => 'User Report',
            'report' => $report
        ]);
    }
    
    protected function getUsersByRole()
    {
        $userModel = new User();
        $users = $userModel->get();
        
        $byRole = [];
        foreach ($users as $user) {
            if (!isset($byRole[$user->role_id])) {
                $byRole[$user->role_id] = 0;
            }
            $byRole[$user->role_id]++;
        }
        
        return $byRole;
    }
    
    protected function getRegistrationTrend()
    {
        $userModel = new User();
        $data = ['labels' => [], 'values' => []];
        
        for ($i = 11; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i months"));
            $data['labels'][] = date('M Y', strtotime("-$i months"));
            
            $users = $userModel->get();
            $count = 0;
            
            foreach ($users as $user) {
                if (date('Y-m', strtotime($user->created_at)) == $month) {
                    $count++;
                }
            }
            
            $data['values'][] = $count;
        }
        
        return $data;
    }
    
    protected function getTopUsers()
    {
        // Get users with most documents or highest spending
        $userModel = new User();
        return $userModel->orderBy('created_at', 'DESC')->limit(10)->get();
    }
}
