<?php

namespace App\Models;

use App\Core\Model;

class User extends Model
{
    protected $table = 'users';
    protected $fillable = [
        'name', 'email', 'password', 'phone', 'role_id', 
        'avatar', 'status', 'email_verified_at'
    ];
    protected $hidden = ['password'];
    
    public function documents()
    {
        return $this->where('user_id', $this->id)->get();
    }
    
    public function subscriptions()
    {
        $subscriptionModel = new Subscription();
        return $subscriptionModel->where('user_id', $this->id)->get();
    }
    
    public function hasRole($roleId)
    {
        return $this->role_id == $roleId;
    }
    
    public function isAdmin()
    {
        return in_array($this->role_id, [ROLE_SUPER_ADMIN, ROLE_ADMIN]);
    }
    
    public function isFranchise()
    {
        return $this->role_id == ROLE_FRANCHISE;
    }
    
    public function isLawyer()
    {
        return $this->role_id == ROLE_LAWYER;
    }
    
    public static function findByEmail($email)
    {
        $instance = new self();
        return $instance->where('email', $email)->first();
    }
    
    public function verifyPassword($password)
    {
        return password_verify($password, $this->password);
    }
}
