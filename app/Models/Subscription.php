<?php

namespace App\Models;

use App\Core\Model;

class Subscription extends Model
{
    protected $table = 'subscriptions';
    protected $fillable = [
        'user_id', 'plan_id', 'status', 'starts_at',
        'ends_at', 'trial_ends_at', 'cancelled_at'
    ];
    
    public function user()
    {
        $userModel = new User();
        return $userModel->find($this->user_id);
    }
    
    public function plan()
    {
        $planModel = new SubscriptionPlan();
        return $planModel->find($this->plan_id);
    }
    
    public function isActive()
    {
        return $this->status === SUBSCRIPTION_ACTIVE 
            && strtotime($this->ends_at) > time();
    }
    
    public function isExpired()
    {
        return strtotime($this->ends_at) < time();
    }
    
    public function onTrial()
    {
        return $this->trial_ends_at && strtotime($this->trial_ends_at) > time();
    }
}
