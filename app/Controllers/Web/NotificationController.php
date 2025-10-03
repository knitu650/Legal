<?php

namespace App\Controllers\Web;

use App\Core\Controller;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        if (!$this->isAuthenticated()) {
            $this->redirect('/login');
            return;
        }
        
        $user = $this->auth();
        $notificationModel = new Notification();
        
        $notifications = $notificationModel->where('user_id', $user->id)
                                          ->orderBy('created_at', 'DESC')
                                          ->get();
        
        $this->view('web/notifications/index', [
            'pageTitle' => 'Notifications',
            'notifications' => $notifications
        ]);
    }
    
    public function markAsRead($id)
    {
        if (!$this->isAuthenticated()) {
            $this->json(['error' => 'Unauthorized'], 401);
            return;
        }
        
        $user = $this->auth();
        $notificationModel = new Notification();
        $notification = $notificationModel->find($id);
        
        if ($notification && $notification->user_id == $user->id) {
            $notification->markAsRead();
            $this->json(['success' => true]);
        } else {
            $this->json(['error' => 'Not found'], 404);
        }
    }
    
    public function markAllAsRead()
    {
        if (!$this->isAuthenticated()) {
            $this->json(['error' => 'Unauthorized'], 401);
            return;
        }
        
        $user = $this->auth();
        $notificationModel = new Notification();
        
        $notifications = $notificationModel->where('user_id', $user->id)
                                          ->where('read_at', 'IS', null)
                                          ->get();
        
        foreach ($notifications as $notification) {
            $notificationModel->update($notification->id, [
                'read_at' => date('Y-m-d H:i:s')
            ]);
        }
        
        $this->json(['success' => true]);
    }
}
