<?php

namespace App\Controllers\User;

use App\Core\Controller;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;

class SubscriptionController extends Controller
{
    public function index()
    {
        $user = $this->auth();
        $subscriptionModel = new Subscription();
        
        $activeSubscription = $subscriptionModel->where('user_id', $user->id)
                                               ->where('status', SUBSCRIPTION_ACTIVE)
                                               ->first();
        
        $subscriptionHistory = $subscriptionModel->where('user_id', $user->id)
                                                ->orderBy('created_at', 'DESC')
                                                ->get();
        
        $this->view('user/subscription/index', [
            'pageTitle' => 'My Subscription',
            'activeSubscription' => $activeSubscription,
            'subscriptionHistory' => $subscriptionHistory
        ]);
    }
    
    public function plans()
    {
        $planModel = new SubscriptionPlan();
        $plans = $planModel->where('is_active', 1)
                          ->orderBy('sort_order', 'ASC')
                          ->get();
        
        $this->view('user/subscription/plans', [
            'pageTitle' => 'Subscription Plans',
            'plans' => $plans
        ]);
    }
    
    public function subscribe()
    {
        $user = $this->auth();
        $planId = $this->request->input('plan_id');
        
        $planModel = new SubscriptionPlan();
        $plan = $planModel->find($planId);
        
        if (!$plan || !$plan->isActive()) {
            flash('error', 'Invalid subscription plan.');
            $this->back();
            return;
        }
        
        // Create subscription
        $subscriptionModel = new Subscription();
        $subscription = $subscriptionModel->create([
            'user_id' => $user->id,
            'plan_id' => $planId,
            'status' => SUBSCRIPTION_ACTIVE,
            'starts_at' => date('Y-m-d H:i:s'),
            'ends_at' => date('Y-m-d H:i:s', strtotime("+{$plan->duration_days} days"))
        ]);
        
        flash('success', 'Subscription activated successfully!');
        $this->redirect('/user/subscription');
    }
    
    public function cancel()
    {
        $user = $this->auth();
        $subscriptionModel = new Subscription();
        
        $subscription = $subscriptionModel->where('user_id', $user->id)
                                         ->where('status', SUBSCRIPTION_ACTIVE)
                                         ->first();
        
        if (!$subscription) {
            flash('error', 'No active subscription found.');
            $this->back();
            return;
        }
        
        $subscriptionModel->update($subscription->id, [
            'status' => SUBSCRIPTION_CANCELLED,
            'cancelled_at' => date('Y-m-d H:i:s')
        ]);
        
        flash('success', 'Subscription cancelled successfully.');
        $this->redirect('/user/subscription');
    }
}
