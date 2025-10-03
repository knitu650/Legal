<?php
namespace App\Models;

use App\Core\Model;

class User extends Model {
    protected $table = 'users';
    
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password', 'phone',
        'address', 'city', 'state', 'country', 'postal_code',
        'role_id', 'status', 'email_verified_at'
    ];

    protected $hidden = ['password', 'remember_token'];

    public function setPasswordAttribute($value) {
        $this->attributes['password'] = password_hash($value, PASSWORD_BCRYPT);
    }

    public function verifyPassword($password) {
        return password_verify($password, $this->password);
    }

    public function getFullNameAttribute() {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function hasRole($role) {
        return $this->role->name === $role;
    }

    public function hasPermission($permission) {
        return $this->role->permissions->contains('name', $permission);
    }

    public function role() {
        return $this->belongsTo(Role::class);
    }

    public function documents() {
        return $this->hasMany(Document::class);
    }

    public function generatePasswordResetToken() {
        $token = bin2hex(random_bytes(32));
        $this->attributes['reset_token'] = $token;
        $this->attributes['reset_token_expires_at'] = date('Y-m-d H:i:s', strtotime('+1 hour'));
        $this->save();
        return $token;
    }

    public function verifyEmail() {
        $this->attributes['email_verified_at'] = date('Y-m-d H:i:s');
        return $this->save();
    }

    public static function findByEmail($email) {
        return static::where('email', '=', $email)->first();
    }

    public static function findByResetToken($token) {
        return static::where('reset_token', '=', $token)
            ->where('reset_token_expires_at', '>', date('Y-m-d H:i:s'))
            ->first();
    }
}