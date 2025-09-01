<?php

namespace App\Core;

use App\Models\User;

/**
 * Auth Class
 * Handles authentication, authorization, and CSRF protection
 */
class Auth
{
    private static $user = null;
    private static $config = null;
    
    /**
     * Initialize authentication system
     */
    public static function init()
    {
        if (self::$config === null) {
            self::$config = require dirname(__DIR__, 2) . '/config/config.php';
        }
    }
    
    /**
     * Attempt to login user
     */
    public static function attempt($email, $password, $remember = false)
    {
        self::init();
        
        // Check login attempts
        if (self::isLoginThrottled($email)) {
            return false;
        }
        
        $userModel = new User();
        $user = $userModel->findByEmail($email);
        
        if (!$user || !password_verify($password, $user['password_hash'])) {
            self::recordFailedLogin($email);
            return false;
        }
        
        // Check if user is active
        if ($user['status'] !== 'active') {
            return false;
        }
        
        // Clear failed login attempts
        self::clearFailedLogins($email);
        
        // Store user in session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['login_time'] = time();
        
        // Regenerate session ID for security
        session_regenerate_id(true);
        
        self::$user = $user;
        
        return true;
    }
    
    /**
     * Logout user
     */
    public static function logout()
    {
        // Clear session data
        unset($_SESSION['user_id']);
        unset($_SESSION['user_role']);
        unset($_SESSION['login_time']);
        
        self::$user = null;
        
        // Regenerate session ID
        session_regenerate_id(true);
    }
    
    /**
     * Check if user is authenticated
     */
    public static function check()
    {
        return self::user() !== null;
    }
    
    /**
     * Check if user is authenticated (alias for check method)
     */
    public static function isAuthenticated()
    {
        return self::check();
    }
    
    /**
     * Get current authenticated user
     */
    public static function user()
    {
        if (self::$user !== null) {
            return self::$user;
        }
        
        if (!isset($_SESSION['user_id'])) {
            return null;
        }
        
        // Check session timeout
        if (self::isSessionExpired()) {
            self::logout();
            return null;
        }
        
        $userModel = new User();
        self::$user = $userModel->findById($_SESSION['user_id']);
        
        return self::$user;
    }
    
    /**
     * Get user ID
     */
    public static function id()
    {
        $user = self::user();
        return $user ? $user['id'] : null;
    }
    
    /**
     * Check if user has specific role
     */
    public static function hasRole($role)
    {
        $user = self::user();
        return $user && $user['role'] === $role;
    }
    
    /**
     * Check if user has any of the specified roles
     */
    public static function hasAnyRole($roles)
    {
        $user = self::user();
        return $user && in_array($user['role'], $roles);
    }
    
    /**
     * Initialize CSRF token
     */
    public static function initCsrfToken()
    {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }
    
    /**
     * Get CSRF token
     */
    public static function getCsrfToken()
    {
        self::initCsrfToken();
        return $_SESSION['csrf_token'];
    }
    
    /**
     * Validate CSRF token
     */
    public static function validateCsrfToken($token)
    {
        return isset($_SESSION['csrf_token']) && 
               hash_equals($_SESSION['csrf_token'], $token);
    }
    
    /**
     * Generate CSRF field for forms
     */
    public static function csrfField()
    {
        self::init();
        $tokenName = self::$config['security']['csrf_token_name'];
        $token = self::getCsrfToken();
        
        return "<input type=\"hidden\" name=\"{$tokenName}\" value=\"{$token}\">";
    }
    
    /**
     * Check if session is expired
     */
    private static function isSessionExpired()
    {
        self::init();
        
        if (!isset($_SESSION['login_time'])) {
            return true;
        }
        
        $sessionLifetime = self::$config['session']['lifetime'];
        return (time() - $_SESSION['login_time']) > $sessionLifetime;
    }
    
    /**
     * Check if login is throttled for email
     */
    private static function isLoginThrottled($email)
    {
        self::init();
        
        $key = 'login_attempts_' . md5($email);
        $attempts = $_SESSION[$key] ?? [];
        $maxAttempts = self::$config['security']['login_attempts_max'];
        $window = self::$config['security']['login_attempts_window'];
        
        // Clean old attempts
        $cutoff = time() - $window;
        $attempts = array_filter($attempts, function($timestamp) use ($cutoff) {
            return $timestamp > $cutoff;
        });
        
        $_SESSION[$key] = $attempts;
        
        return count($attempts) >= $maxAttempts;
    }
    
    /**
     * Record failed login attempt
     */
    private static function recordFailedLogin($email)
    {
        $key = 'login_attempts_' . md5($email);
        
        if (!isset($_SESSION[$key])) {
            $_SESSION[$key] = [];
        }
        
        $_SESSION[$key][] = time();
    }
    
    /**
     * Clear failed login attempts
     */
    private static function clearFailedLogins($email)
    {
        $key = 'login_attempts_' . md5($email);
        unset($_SESSION[$key]);
    }
    
    /**
     * Hash password
     */
    public static function hashPassword($password)
    {
        self::init();
        $cost = self::$config['security']['password_cost'];
        return password_hash($password, PASSWORD_DEFAULT, ['cost' => $cost]);
    }
    
    /**
     * Verify password
     */
    public static function verifyPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }
}
