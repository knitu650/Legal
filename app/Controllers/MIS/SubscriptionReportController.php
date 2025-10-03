<?php

namespace App\Controllers\MIS;

use App\Core\Controller;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;

class SubscriptionReportController extends Controller
{
    public function index()
    {
        $subscriptionModel = new Subscription();
        
        $report = [
            'total_subscriptions' => $subscriptionModel->count(),
            'active_subscriptions' => $subscriptionModel->where('status', SUBSCRIPTION_ACTIVE)->count(),
            'expired_subscriptions' => $subscriptionModel->where('status', SUBSCRIPTION_EXPIRED)->count(),
            'cancelled_subscriptions' => $subscriptionModel->where('status', SUBSCRIPTION_CANCELLED)->count(),
            'by_plan' => $this->getSubscriptionsByPlan(),
            'subscription_trend' => $this->getSubscriptionTrend(),
            'churn_rate' => $this->getChurnRate(),
            'mrr' => $this->getMRR(),
        ];
        
        $this->view('mis/reports/subscriptions', [
            'pageTitle' => 'Subscription Report',
            'report' => $report
        ]);
    }
    
    protected function getSubscriptionsByPlan()
    {
        $subscriptionModel = new Subscription();
        $subscriptions = $subscriptionModel->where('status', SUBSCRIPTION_ACTIVE)->get();
        
        $byPlan = [];
        foreach ($subscriptions as $sub) {
            if (!isset($byPlan[$sub->plan_id])) {
                $byPlan[$sub->plan_id] = 0;
            }
            $byPlan[$sub->plan_id]++;
        }
        
        return $byPlan;
    }
    
    protected function getSubscriptionTrend()
    {
        $subscriptionModel = new Subscription();
        $data = ['labels' => [], 'values' => []];
        
        for ($i = 11; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i months"));
            $data['labels'][] = date('M Y', strtotime("-$i months"));
            
            $subscriptions = $subscriptionModel->get();
            $count = 0;
            
            foreach ($subscriptions as $sub) {
                if (date('Y-m', strtotime($sub->created_at)) == $month) {
                    $count++;
                }
            }
            
            $data['values'][] = $count;
        }
        
        return $data;
    }
    
    protected function getChurnRate()
    {
        $subscriptionModel = new Subscription();
        
        $currentMonth = date('Y-m');
        $previousMonth = date('Y-m', strtotime('-1 month'));
        
        $activeLastMonth = 0;
        $cancelledThisMonth = 0;
        
        $subscriptions = $subscriptionModel->get();
        
        foreach ($subscriptions as $sub) {
            if ($sub->cancelled_at) {
                $cancelMonth = date('Y-m', strtotime($sub->cancelled_at));
                if ($cancelMonth == $currentMonth) {
                    $cancelledThisMonth++;
                }
            }
        }
        
        // Simplified calculation
        if ($activeLastMonth == 0) return 0;
        
        return round(($cancelledThisMonth / $activeLastMonth) * 100, 2);
    }
    
    protected function getMRR()
    {
        // Monthly Recurring Revenue
        $subscriptionModel = new Subscription();
        $planModel = new SubscriptionPlan();
        
        $activeSubs = $subscriptionModel->where('status', SUBSCRIPTION_ACTIVE)->get();
        $mrr = 0;
        
        foreach ($activeSubs as $sub) {
            $plan = $planModel->find($sub->plan_id);
            if ($plan) {
                $monthlyPrice = $plan->getMonthlyPrice();
                $mrr += $monthlyPrice;
            }
        }
        
        return $mrr;
    }
}
