<?php

namespace App\Models;

use App\Core\Model;

class ActivityLog extends Model
{
    protected $table = 'activity_logs';
    protected $fillable = [
        'user_id', 'activity', 'description', 'properties'
    ];
    
    public $timestamps = false;
    
    public function user()
    {
        $userModel = new User();
        return $userModel->find($this->user_id);
    }
    
    public function getProperties()
    {
        return json_decode($this->properties, true) ?? [];
    }
    
    public static function log($userId, $activity, $description = '', $properties = [])
    {
        $instance = new self();
        return $instance->create([
            'user_id' => $userId,
            'activity' => $activity,
            'description' => $description,
            'properties' => json_encode($properties)
        ]);
    }
    
    public static function userActivities($userId, $limit = 10)
    {
        $instance = new self();
        return $instance->where('user_id', $userId)
                       ->orderBy('created_at', 'DESC')
                       ->limit($limit)
                       ->get();
    }
}
