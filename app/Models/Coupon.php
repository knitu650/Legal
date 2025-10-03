<?php

namespace App\Models;

use App\Core\Model;

class Coupon extends Model
{
    protected $table = 'coupons';
    protected $fillable = [
        'code', 'type', 'value', 'min_purchase', 'max_discount',
        'usage_limit', 'used_count', 'starts_at', 'expires_at', 'is_active'
    ];
    
    public function isActive()
    {
        return $this->is_active == 1 
            && strtotime($this->starts_at) <= time() 
            && strtotime($this->expires_at) >= time();
    }
    
    public function isExpired()
    {
        return strtotime($this->expires_at) < time();
    }
    
    public function hasReachedLimit()
    {
        return $this->usage_limit && $this->used_count >= $this->usage_limit;
    }
    
    public function canBeUsed()
    {
        return $this->isActive() && !$this->hasReachedLimit();
    }
    
    public function calculateDiscount($amount)
    {
        if ($amount < $this->min_purchase) {
            return 0;
        }
        
        $discount = 0;
        
        if ($this->type === 'percentage') {
            $discount = ($amount * $this->value) / 100;
        } else {
            $discount = $this->value;
        }
        
        if ($this->max_discount && $discount > $this->max_discount) {
            $discount = $this->max_discount;
        }
        
        return $discount;
    }
    
    public function incrementUsage()
    {
        return $this->update($this->id, [
            'used_count' => $this->used_count + 1
        ]);
    }
    
    public static function findByCode($code)
    {
        $instance = new self();
        return $instance->where('code', strtoupper($code))->first();
    }
}
