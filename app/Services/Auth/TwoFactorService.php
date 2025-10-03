<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Services\Notification\SMSService;
use App\Core\Session;

class TwoFactorService
{
    protected $smsService;
    
    public function __construct()
    {
        $this->smsService = new SMSService();
    }
    
    public function generateOTP($userId)
    {
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Store OTP in session with expiry
        Session::set('2fa_otp_' . $userId, [
            'code' => $otp,
            'expires_at' => time() + 600 // 10 minutes
        ]);
        
        return $otp;
    }
    
    public function sendOTP($userId)
    {
        $userModel = new User();
        $user = $userModel->find($userId);
        
        if (!$user || !$user->phone) {
            return false;
        }
        
        $otp = $this->generateOTP($userId);
        
        // Send OTP via SMS
        $this->smsService->sendOTP($user->phone, $otp);
        
        return true;
    }
    
    public function verifyOTP($userId, $code)
    {
        $otpData = Session::get('2fa_otp_' . $userId);
        
        if (!$otpData) {
            return false;
        }
        
        // Check if expired
        if (time() > $otpData['expires_at']) {
            Session::remove('2fa_otp_' . $userId);
            return false;
        }
        
        // Verify code
        if ($otpData['code'] === $code) {
            Session::remove('2fa_otp_' . $userId);
            Session::set('2fa_verified_' . $userId, true);
            return true;
        }
        
        return false;
    }
    
    public function enable2FA($userId)
    {
        $userModel = new User();
        $userModel->update($userId, [
            'two_factor_enabled' => 1
        ]);
        
        return true;
    }
    
    public function disable2FA($userId)
    {
        $userModel = new User();
        $userModel->update($userId, [
            'two_factor_enabled' => 0
        ]);
        
        return true;
    }
}
