<?php

namespace App\Models;

use App\Core\Model;

class Notification extends Model
{
    protected $table = 'notifications';
    protected $fillable = [
        'user_id', 'type', 'title', 'message', 'data', 'read_at'
    ];
    
    public function user()
    {
        $userModel = new User();
        return $userModel->find($this->user_id);
    }
    
    public function isRead()
    {
        return !is_null($this->read_at);
    }
    
    public function isUnread()
    {
        return is_null($this->read_at);
    }
    
    public function markAsRead()
    {
        return $this->update($this->id, [
            'read_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    public function markAsUnread()
    {
        return $this->update($this->id, [
            'read_at' => null
        ]);
    }
    
    public function getData()
    {
        return json_decode($this->data, true) ?? [];
    }
    
    public static function send($userId, $type, $title, $message, $data = [])
    {
        $instance = new self();
        return $instance->create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => json_encode($data)
        ]);
    }
    
    public static function unreadCount($userId)
    {
        $instance = new self();
        return $instance->where('user_id', $userId)
                       ->where('read_at', 'IS', null)
                       ->count();
    }
}
