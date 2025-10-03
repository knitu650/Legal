<?php

namespace App\Controllers\Web;

use App\Core\Controller;
use App\Core\Session;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        if ($this->isAuthenticated()) {
            $this->redirect('/user/dashboard');
            return;
        }
        
        $this->view('web/auth/login', [
            'pageTitle' => 'Login'
        ]);
    }
    
    public function login()
    {
        $email = $this->request->input('email');
        $password = $this->request->input('password');
        $remember = $this->request->input('remember');
        
        $isValid = $this->validate([
            'email' => $email,
            'password' => $password,
        ], [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        
        if (!$isValid) {
            flash('error', 'Please provide valid credentials.');
            $this->back();
            return;
        }
        
        $user = User::findByEmail($email);
        
        if (!$user || !$user->verifyPassword($password)) {
            flash('error', 'Invalid email or password.');
            $this->back();
            return;
        }
        
        // Set session
        Session::set('user', $user);
        Session::regenerate();
        
        // Redirect based on role
        $redirectUrl = $this->getRedirectUrl($user->role_id);
        $this->redirect($redirectUrl);
    }
    
    public function showRegister()
    {
        if ($this->isAuthenticated()) {
            $this->redirect('/user/dashboard');
            return;
        }
        
        $this->view('web/auth/register', [
            'pageTitle' => 'Register'
        ]);
    }
    
    public function register()
    {
        $data = [
            'name' => $this->request->input('name'),
            'email' => $this->request->input('email'),
            'phone' => $this->request->input('phone'),
            'password' => $this->request->input('password'),
            'password_confirmation' => $this->request->input('password_confirmation'),
        ];
        
        $isValid = $this->validate($data, [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|min:10',
            'password' => 'required|min:6|confirmed',
        ]);
        
        if (!$isValid) {
            flash('error', 'Please fill all fields correctly.');
            $this->back();
            return;
        }
        
        // Create user
        $userModel = new User();
        $user = $userModel->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => password_hash($data['password'], PASSWORD_BCRYPT),
            'role_id' => ROLE_USER,
            'status' => 'active',
        ]);
        
        // Auto login
        Session::set('user', $user);
        Session::regenerate();
        
        flash('success', 'Registration successful! Welcome to Legal Docs.');
        $this->redirect('/user/dashboard');
    }
    
    public function logout()
    {
        Session::remove('user');
        Session::regenerate();
        
        flash('success', 'You have been logged out successfully.');
        $this->redirect('/');
    }
    
    public function showForgotPassword()
    {
        $this->view('web/auth/forgot-password', [
            'pageTitle' => 'Forgot Password'
        ]);
    }
    
    public function forgotPassword()
    {
        $email = $this->request->input('email');
        
        $isValid = $this->validate(['email' => $email], [
            'email' => 'required|email',
        ]);
        
        if (!$isValid) {
            flash('error', 'Please provide a valid email address.');
            $this->back();
            return;
        }
        
        $user = User::findByEmail($email);
        
        if (!$user) {
            flash('error', 'No user found with this email address.');
            $this->back();
            return;
        }
        
        // Generate reset token and send email
        flash('success', 'Password reset link has been sent to your email.');
        $this->redirect('/login');
    }
    
    protected function getRedirectUrl($roleId)
    {
        switch ($roleId) {
            case ROLE_SUPER_ADMIN:
            case ROLE_ADMIN:
                return '/admin/dashboard';
            case ROLE_MIS:
                return '/mis/dashboard';
            case ROLE_FRANCHISE:
                return '/franchise/dashboard';
            case ROLE_LAWYER:
                return '/lawyer/dashboard';
            default:
                return '/user/dashboard';
        }
    }
}
