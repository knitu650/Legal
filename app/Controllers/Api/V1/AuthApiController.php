<?php

namespace App\Controllers\Api\V1;

use App\Core\Controller;
use App\Services\Auth\AuthService;

class AuthApiController extends Controller
{
    protected $authService;
    
    public function __construct($request, $response)
    {
        parent::__construct($request, $response);
        $this->authService = new AuthService();
    }
    
    public function login()
    {
        $email = $this->request->input('email');
        $password = $this->request->input('password');
        
        if (!$email || !$password) {
            $this->json(['error' => 'Email and password are required'], 400);
            return;
        }
        
        $result = $this->authService->login($email, $password);
        
        if ($result['success']) {
            $token = base64_encode($email . ':' . time());
            
            $this->json([
                'success' => true,
                'token' => $token,
                'user' => $result['user']
            ]);
        } else {
            $this->json(['error' => $result['message']], 401);
        }
    }
    
    public function register()
    {
        $data = [
            'name' => $this->request->input('name'),
            'email' => $this->request->input('email'),
            'phone' => $this->request->input('phone'),
            'password' => $this->request->input('password'),
        ];
        
        $result = $this->authService->register($data);
        
        if ($result['success']) {
            $token = base64_encode($data['email'] . ':' . time());
            
            $this->json([
                'success' => true,
                'token' => $token,
                'user' => $result['user']
            ], 201);
        } else {
            $this->json(['error' => $result['message']], 400);
        }
    }
    
    public function logout()
    {
        $this->authService->logout();
        
        $this->json(['success' => true, 'message' => 'Logged out successfully']);
    }
}
