<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Coupon;

class CouponController extends Controller
{
    public function index()
    {
        $couponModel = new Coupon();
        $coupons = $couponModel->orderBy('created_at', 'DESC')->get();
        
        $this->view('admin/coupons/index', [
            'pageTitle' => 'Coupon Management',
            'coupons' => $coupons
        ]);
    }
    
    public function store()
    {
        $data = [
            'code' => strtoupper($this->request->input('code')),
            'type' => $this->request->input('type'),
            'value' => $this->request->input('value'),
            'min_purchase' => $this->request->input('min_purchase', 0),
            'max_discount' => $this->request->input('max_discount'),
            'usage_limit' => $this->request->input('usage_limit'),
            'starts_at' => $this->request->input('starts_at'),
            'expires_at' => $this->request->input('expires_at'),
            'is_active' => $this->request->input('is_active', 1),
        ];
        
        $couponModel = new Coupon();
        $couponModel->create($data);
        
        flash('success', 'Coupon created successfully!');
        $this->redirect('/admin/coupons');
    }
    
    public function update($id)
    {
        $data = [
            'code' => strtoupper($this->request->input('code')),
            'type' => $this->request->input('type'),
            'value' => $this->request->input('value'),
            'min_purchase' => $this->request->input('min_purchase'),
            'max_discount' => $this->request->input('max_discount'),
            'usage_limit' => $this->request->input('usage_limit'),
            'starts_at' => $this->request->input('starts_at'),
            'expires_at' => $this->request->input('expires_at'),
            'is_active' => $this->request->input('is_active'),
        ];
        
        $couponModel = new Coupon();
        $couponModel->update($id, $data);
        
        flash('success', 'Coupon updated successfully!');
        $this->redirect('/admin/coupons');
    }
    
    public function delete($id)
    {
        $couponModel = new Coupon();
        $couponModel->delete($id);
        
        flash('success', 'Coupon deleted successfully!');
        $this->redirect('/admin/coupons');
    }
    
    public function usage()
    {
        $couponModel = new Coupon();
        $coupons = $couponModel->orderBy('used_count', 'DESC')->get();
        
        $this->view('admin/coupons/usage', [
            'pageTitle' => 'Coupon Usage Statistics',
            'coupons' => $coupons
        ]);
    }
}
