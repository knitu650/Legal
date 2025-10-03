<?php

namespace App\Services\Subscription;

use App\Models\Subscription;
use App\Models\SubscriptionPlan;

class SubscriptionService
{
    public function subscribe($userId, $planId, $startDate = null)
    {
        $planModel = new SubscriptionPlan();
        $plan = $planModel->find($planId);
        
        if (!$plan || !$plan->isActive()) {
            return [
                'success' => false,
                'message' => 'Invalid subscription plan'
            ];
        }
        
        // Cancel any existing active subscriptions
        $this->cancelActiveSubscriptions($userId);
        
        // Create new subscription
        $startDate = $startDate ?? date('Y-m-d H:i:s');
        $endDate = date('Y-m-d H:i:s', strtotime($startDate . ' +' . $plan->duration_days . ' days'));
        
        $subscriptionModel = new Subscription();
        $subscription = $subscriptionModel->create([
            'user_id' => $userId,
            'plan_id' => $planId,
            'status' => SUBSCRIPTION_ACTIVE,
            'starts_at' => $startDate,
            'ends_at' => $endDate
        ]);
        
        return [
            'success' => true,
            'subscription' => $subscription
        ];
    }
    
    public function cancelActiveSubscriptions($userId)
    {
        $subscriptionModel = new Subscription();
        $activeSubscriptions = $subscriptionModel->where('user_id', $userId)
                                                 ->where('status', SUBSCRIPTION_ACTIVE)
                                                 ->get();
        
        foreach ($activeSubscriptions as $sub) {
            $subscriptionModel->update($sub->id, [
                'status' => SUBSCRIPTION_CANCELLED,
                'cancelled_at' => date('Y-m-d H:i:s')
            ]);
        }
    }
    
    public function renew($subscriptionId)
    {
        $subscriptionModel = new Subscription();
        $subscription = $subscriptionModel->find($subscriptionId);
        
        if (!$subscription) {
            return false;
        }
        
        $planModel = new SubscriptionPlan();
        $plan = $planModel->find($subscription->plan_id);
        
        // Extend subscription
        $newEndDate = date('Y-m-d H:i:s', strtotime($subscription->ends_at . ' +' . $plan->duration_days . ' days'));
        
        $subscriptionModel->update($subscriptionId, [
            'ends_at' => $newEndDate,
            'status' => SUBSCRIPTION_ACTIVE
        ]);
        
        return true;
    }
    
    public function upgrade($userId, $newPlanId)
    {
        // Cancel current and subscribe to new plan
        return $this->subscribe($userId, $newPlanId);
    }
    
    public function downgrade($userId, $newPlanId)
    {
        // Schedule downgrade at end of current period
        return $this->subscribe($userId, $newPlanId);
    }
}
