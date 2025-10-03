<?php
namespace App\Services;

use App\Models\User;

class Auth {
    private static $instance = null;
    private $user = null;
    
    private function __construct() {
        $this->checkSession();
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function checkSession() {
        if (isset($_SESSION['user_id'])) {
            $this->user = User::find($_SESSION['user_id']);
        }
    }

    public function login(User $user) {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['last_activity'] = time();
        $this->user = $user;
    }

    public function logout() {
        session_destroy();
        $this->user = null;
    }

    public function check() {
        return $this->user !== null;
    }

    public function user() {
        return $this->user;
    }

    public function id() {
        return $this->user ? $this->user->id : null;
    }

    public function guest() {
        return !$this->check();
    }
}