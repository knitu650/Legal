<?php

namespace App\Repositories;

use App\Models\Notification;

class NotificationRepository
{
    protected $model;
    
    public function __construct()
    {
        $this->model = new Notification();
    }
    
    public function getUserNotifications($userId, $limit = 50)
    {
        return $this->model->where('user_id', $userId)
                          ->orderBy('created_at', 'DESC')
                          ->limit($limit)
                          ->get();
    }
    
    public function getUnreadNotifications($userId)
    {
        return $this->model->where('user_id', $userId)
                          ->where('read_at', 'IS', null)
                          ->orderBy('created_at', 'DESC')
                          ->get();
    }
    
    public function getUnreadCount($userId)
    {
        return Notification::unreadCount($userId);
    }
    
    public function markAsRead($notificationId)
    {
        $notification = $this->model->find($notificationId);
        
        if ($notification) {
            return $notification->markAsRead();
        }
        
        return false;
    }
    
    public function markAllAsRead($userId)
    {
        $notifications = $this->getUnreadNotifications($userId);
        
        foreach ($notifications as $notification) {
            $this->model->update($notification->id, [
                'read_at' => date('Y-m-d H:i:s')
            ]);
        }
        
        return true;
    }
}
