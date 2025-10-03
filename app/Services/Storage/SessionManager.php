<?php

namespace App\Services\Storage;

class SessionManager
{
    protected $sessionPath;
    protected $sessionName = 'LDMS_SESSION';
    protected $lifetime = 7200; // 2 hours
    
    public function __construct()
    {
        $this->sessionPath = storage_path('sessions/');
        
        if (!is_dir($this->sessionPath)) {
            mkdir($this->sessionPath, 0755, true);
        }
        
        // Configure session
        ini_set('session.save_path', $this->sessionPath);
        ini_set('session.gc_maxlifetime', $this->lifetime);
        ini_set('session.cookie_lifetime', $this->lifetime);
        ini_set('session.cookie_httponly', 1);
        ini_set('session.use_strict_mode', 1);
    }
    
    /**
     * Start session
     */
    public function start()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_name($this->sessionName);
            
            if (session_start()) {
                // Regenerate session ID periodically for security
                if (!isset($_SESSION['created'])) {
                    $_SESSION['created'] = time();
                } elseif (time() - $_SESSION['created'] > 1800) {
                    // Regenerate after 30 minutes
                    session_regenerate_id(true);
                    $_SESSION['created'] = time();
                }
                
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Set session value
     */
    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }
    
    /**
     * Get session value
     */
    public function get($key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }
    
    /**
     * Check if session key exists
     */
    public function has($key)
    {
        return isset($_SESSION[$key]);
    }
    
    /**
     * Remove session value
     */
    public function remove($key)
    {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }
    
    /**
     * Flash message (one-time message)
     */
    public function flash($key, $value)
    {
        $_SESSION['_flash'][$key] = $value;
    }
    
    /**
     * Get flash message
     */
    public function getFlash($key, $default = null)
    {
        $value = $_SESSION['_flash'][$key] ?? $default;
        
        if (isset($_SESSION['_flash'][$key])) {
            unset($_SESSION['_flash'][$key]);
        }
        
        return $value;
    }
    
    /**
     * Regenerate session ID
     */
    public function regenerate($deleteOld = true)
    {
        session_regenerate_id($deleteOld);
        $_SESSION['created'] = time();
    }
    
    /**
     * Destroy session
     */
    public function destroy()
    {
        $_SESSION = [];
        
        // Delete session cookie
        if (isset($_COOKIE[$this->sessionName])) {
            setcookie($this->sessionName, '', time() - 3600, '/');
        }
        
        return session_destroy();
    }
    
    /**
     * Get all session data
     */
    public function all()
    {
        return $_SESSION;
    }
    
    /**
     * Get session ID
     */
    public function getId()
    {
        return session_id();
    }
    
    /**
     * Clean old sessions
     */
    public function cleanOldSessions()
    {
        $files = glob($this->sessionPath . 'sess_*');
        $cleaned = 0;
        $now = time();
        
        foreach ($files as $file) {
            if (is_file($file)) {
                if ($now - filemtime($file) >= $this->lifetime) {
                    if (unlink($file)) {
                        $cleaned++;
                    }
                }
            }
        }
        
        return $cleaned;
    }
    
    /**
     * Get session statistics
     */
    public function getStats()
    {
        $files = glob($this->sessionPath . 'sess_*');
        $totalSize = 0;
        $active = 0;
        $expired = 0;
        $now = time();
        
        foreach ($files as $file) {
            if (!is_file($file)) {
                continue;
            }
            
            $totalSize += filesize($file);
            
            if ($now - filemtime($file) >= $this->lifetime) {
                $expired++;
            } else {
                $active++;
            }
        }
        
        return [
            'total_sessions' => count($files),
            'active_sessions' => $active,
            'expired_sessions' => $expired,
            'total_size' => $totalSize,
            'total_size_formatted' => $this->formatBytes($totalSize)
        ];
    }
    
    protected function formatBytes($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }
}
