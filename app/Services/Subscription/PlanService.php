<?php

namespace App\Services\Subscription;

use App\Models\SubscriptionPlan;

class PlanService
{
    public function getAllPlans()
    {
        $planModel = new SubscriptionPlan();
        return $planModel->where('is_active', 1)
                        ->orderBy('sort_order', 'ASC')
                        ->get();
    }
    
    public function getPlanById($id)
    {
        $planModel = new SubscriptionPlan();
        return $planModel->find($id);
    }
    
    public function comparePlans($planId1, $planId2)
    {
        $plan1 = $this->getPlanById($planId1);
        $plan2 = $this->getPlanById($planId2);
        
        if (!$plan1 || !$plan2) {
            return null;
        }
        
        return [
            'plan1' => $plan1,
            'plan2' => $plan2,
            'price_difference' => abs($plan1->price - $plan2->price),
            'features_comparison' => $this->compareFeatures($plan1, $plan2)
        ];
    }
    
    protected function compareFeatures($plan1, $plan2)
    {
        $features1 = $plan1->getFeatures();
        $features2 = $plan2->getFeatures();
        
        return [
            'plan1_exclusive' => array_diff($features1, $features2),
            'plan2_exclusive' => array_diff($features2, $features1),
            'common' => array_intersect($features1, $features2)
        ];
    }
}
