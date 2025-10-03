<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;

class SubscriptionManagementController extends Controller
{
    public function index()
    {
        $subscriptionModel = new Subscription();
        $subscriptions = $subscriptionModel->orderBy('created_at', 'DESC')->get();
        
        $this->view('admin/subscriptions/index', [
            'pageTitle' => 'Subscription Management',
            'subscriptions' => $subscriptions
        ]);
    }
    
    public function plans()
    {
        $planModel = new SubscriptionPlan();
        $plans = $planModel->orderBy('sort_order', 'ASC')->get();
        
        $this->view('admin/subscriptions/plans', [
            'pageTitle' => 'Subscription Plans',
            'plans' => $plans
        ]);
    }
    
    public function storePlan()
    {
        $data = [
            'name' => $this->request->input('name'),
            'slug' => $this->request->input('slug'),
            'description' => $this->request->input('description'),
            'price' => $this->request->input('price'),
            'duration_days' => $this->request->input('duration_days'),
            'features' => json_encode($this->request->input('features', [])),
            'is_active' => $this->request->input('is_active', 1),
            'sort_order' => $this->request->input('sort_order', 0),
        ];
        
        $planModel = new SubscriptionPlan();
        $planModel->create($data);
        
        flash('success', 'Plan created successfully!');
        $this->redirect('/admin/subscriptions/plans');
    }
    
    public function updatePlan($id)
    {
        $data = [
            'name' => $this->request->input('name'),
            'slug' => $this->request->input('slug'),
            'description' => $this->request->input('description'),
            'price' => $this->request->input('price'),
            'duration_days' => $this->request->input('duration_days'),
            'features' => json_encode($this->request->input('features', [])),
            'is_active' => $this->request->input('is_active'),
            'sort_order' => $this->request->input('sort_order'),
        ];
        
        $planModel = new SubscriptionPlan();
        $planModel->update($id, $data);
        
        flash('success', 'Plan updated successfully!');
        $this->redirect('/admin/subscriptions/plans');
    }
}
