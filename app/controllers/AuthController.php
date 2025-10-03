<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use App\Models\User;
use App\Services\Mailer;

class AuthController extends Controller {
    public function showLogin() {
        return $this->view('auth.login');
    }

    public function showRegister() {
        return $this->view('auth.register');
    }

    public function login(Request $request) {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        $user = User::findByEmail($data['email']);
        
        if (!$user || !$user->verifyPassword($data['password'])) {
            return $this->back()->withErrors(['email' => 'Invalid credentials']);
        }

        if (!$user->email_verified_at) {
            return $this->back()->withErrors(['email' => 'Please verify your email first']);
        }

        $this->auth()->login($user);
        return $this->redirect('/dashboard');
    }

    public function register(Request $request) {
        $data = $request->validate([
            'first_name' => ['required', 'min:2'],
            'last_name' => ['required', 'min:2'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8', 'confirmed'],
            'phone' => ['required'],
            'address' => ['required'],
            'city' => ['required'],
            'state' => ['required'],
            'country' => ['required'],
            'postal_code' => ['required']
        ]);

        $user = User::create($data);
        $token = bin2hex(random_bytes(32));
        
        // Send verification email
        $mailer = new Mailer();
        $mailer->sendVerificationEmail($user, $token);

        return $this->redirect('/login')
            ->with('success', 'Registration successful! Please check your email to verify your account.');
    }

    public function showForgotPassword() {
        return $this->view('auth.forgot-password');
    }

    public function forgotPassword(Request $request) {
        $data = $request->validate([
            'email' => ['required', 'email']
        ]);

        $user = User::findByEmail($data['email']);
        
        if ($user) {
            $token = $user->generatePasswordResetToken();
            
            // Send password reset email
            $mailer = new Mailer();
            $mailer->sendPasswordResetEmail($user, $token);
        }

        return $this->back()
            ->with('success', 'If an account exists with this email, you will receive password reset instructions.');
    }

    public function showResetPassword($token) {
        $user = User::findByResetToken($token);
        
        if (!$user) {
            return $this->redirect('/forgot-password')
                ->with('error', 'Invalid or expired password reset token');
        }

        return $this->view('auth.reset-password', ['token' => $token]);
    }

    public function resetPassword(Request $request) {
        $data = $request->validate([
            'token' => ['required'],
            'password' => ['required', 'min:8', 'confirmed']
        ]);

        $user = User::findByResetToken($data['token']);
        
        if (!$user) {
            return $this->back()
                ->with('error', 'Invalid or expired password reset token');
        }

        $user->password = $data['password'];
        $user->reset_token = null;
        $user->reset_token_expires_at = null;
        $user->save();

        return $this->redirect('/login')
            ->with('success', 'Password has been reset successfully. Please login with your new password.');
    }

    public function verifyEmail($token) {
        $user = User::where('verification_token', $token)->first();
        
        if (!$user) {
            return $this->redirect('/login')
                ->with('error', 'Invalid verification token');
        }

        $user->verifyEmail();
        return $this->redirect('/login')
            ->with('success', 'Email verified successfully. You can now login.');
    }

    public function logout() {
        $this->auth()->logout();
        return $this->redirect('/login');
    }
}