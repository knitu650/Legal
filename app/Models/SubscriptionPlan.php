<?php

namespace App\Models;

use App\Core\Model;

class SubscriptionPlan extends Model
{
    protected $table = 'subscription_plans';
    protected $fillable = [
        'name', 'slug', 'description', 'price', 
        'duration_days', 'features', 'is_active', 'sort_order'
    ];
    
    public function subscriptions()
    {
        $subscriptionModel = new Subscription();
        return $subscriptionModel->where('plan_id', $this->id)->get();
    }
    
    public function getFeatures()
    {
        return json_decode($this->features, true) ?? [];
    }
    
    public function isActive()
    {
        return $this->is_active == 1;
    }
    
    public function isMonthly()
    {
        return $this->duration_days == 30;
    }
    
    public function isYearly()
    {
        return $this->duration_days == 365;
    }
    
    public function activeSubscriptionsCount()
    {
        $subscriptionModel = new Subscription();
        return $subscriptionModel->where('plan_id', $this->id)
                                 ->where('status', SUBSCRIPTION_ACTIVE)
                                 ->count();
    }
    
    public function getMonthlyPrice()
    {
        if ($this->isYearly()) {
            return round($this->price / 12, 2);
        }
        return $this->price;
    }
}
