<?php

namespace App\Controllers\User;

use App\Core\Controller;
use App\Models\Referral;

class ReferralController extends Controller
{
    public function index()
    {
        $user = $this->auth();
        
        // Get or create referral code
        $referralCode = Referral::generateCode($user->id);
        
        $referrals = Referral::getUserReferrals($user->id);
        $totalRewards = Referral::getTotalRewards($user->id);
        
        $this->view('user/referral/index', [
            'pageTitle' => 'Refer & Earn',
            'referralCode' => $referralCode,
            'referrals' => $referrals,
            'totalRewards' => $totalRewards
        ]);
    }
}
