<?php

namespace App\Controllers\Api\V1;

use App\Core\Controller;
use App\Models\User;

class UserApiController extends Controller
{
    public function me()
    {
        if (!$this->isAuthenticated()) {
            $this->json(['error' => 'Unauthenticated'], 401);
            return;
        }
        
        $user = $this->auth();
        
        $this->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'role_id' => $user->role_id,
            'status' => $user->status,
            'created_at' => $user->created_at
        ]);
    }
    
    public function update()
    {
        if (!$this->isAuthenticated()) {
            $this->json(['error' => 'Unauthenticated'], 401);
            return;
        }
        
        $user = $this->auth();
        
        $data = [
            'name' => $this->request->input('name'),
            'phone' => $this->request->input('phone'),
        ];
        
        $userModel = new User();
        $updatedUser = $userModel->update($user->id, $data);
        
        $this->json([
            'success' => true,
            'user' => $updatedUser
        ]);
    }
}
