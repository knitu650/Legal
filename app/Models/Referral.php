<?php

namespace App\Models;

use App\Core\Model;

class Referral extends Model
{
    protected $table = 'referrals';
    protected $fillable = [
        'referrer_id', 'referred_id', 'referral_code', 
        'status', 'reward_amount', 'rewarded_at'
    ];
    
    public function referrer()
    {
        $userModel = new User();
        return $userModel->find($this->referrer_id);
    }
    
    public function referred()
    {
        $userModel = new User();
        return $userModel->find($this->referred_id);
    }
    
    public function isPending()
    {
        return $this->status === 'pending';
    }
    
    public function isCompleted()
    {
        return $this->status === 'completed';
    }
    
    public function complete($rewardAmount)
    {
        return $this->update($this->id, [
            'status' => 'completed',
            'reward_amount' => $rewardAmount,
            'rewarded_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    public static function generateCode($userId)
    {
        return 'REF' . str_pad($userId, 6, '0', STR_PAD_LEFT) . strtoupper(substr(md5(uniqid()), 0, 4));
    }
    
    public static function findByCode($code)
    {
        $instance = new self();
        return $instance->where('referral_code', strtoupper($code))->first();
    }
    
    public static function getUserReferrals($userId)
    {
        $instance = new self();
        return $instance->where('referrer_id', $userId)->get();
    }
    
    public static function getTotalRewards($userId)
    {
        $referrals = self::getUserReferrals($userId);
        $total = 0;
        foreach ($referrals as $referral) {
            if ($referral->isCompleted()) {
                $total += $referral->reward_amount;
            }
        }
        return $total;
    }
}
