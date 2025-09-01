<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Request;
use App\Models\User;

/**
 * Authentication Controller
 * Handles login, logout, and authentication-related functionality
 */
class AuthController extends Controller
{
    private $userModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->userModel = new User();
    }
    
    /**
     * Show login form
     */
    public function showLogin()
    {
        // Redirect if already authenticated
        if (Auth::check()) {
            $this->redirectToDashboard();
            return;
        }
        
        // Initialize CSRF token
        Auth::initCsrfToken();
        
        $data = [
            'csrfTokenName' => $this->config['security']['csrf_token_name'],
            'csrfToken' => $_SESSION['csrf_token']
        ];
        
        $this->render('auth/login', $data, 'front');
    }
    
    /**
     * Handle login attempt
     */
    public function login()
    {
        if (!$this->validateCsrf()) {
            return;
        }
        
        $request = new Request();
        $email = $request->input('email');
        $password = $request->input('password');
        $remember = $request->input('remember', false);
        
        // Validate input
        $errors = $this->validateLoginInput($email, $password);
        
        if (!empty($errors)) {
            $this->setFlashMessage('Please correct the errors below.', 'error');
            $_SESSION['validation_errors'] = $errors;
            $_SESSION['old_input'] = $request->only(['email']);
            $this->redirect('/login');
            return;
        }
        
        // Attempt authentication
        if (Auth::attempt($email, $password, $remember)) {
            // Successful login
            $this->setFlashMessage('Welcome back!', 'success');
            
            // Redirect to intended URL or dashboard
            $intendedUrl = $_SESSION['intended_url'] ?? null;
            unset($_SESSION['intended_url']);
            
            if ($intendedUrl) {
                $this->redirect($intendedUrl);
            } else {
                $this->redirectToDashboard();
            }
        } else {
            // Failed login
            $this->setFlashMessage('Invalid email or password. Please try again.', 'error');
            $_SESSION['old_input'] = $request->only(['email']);
            $this->redirect('/login');
        }
    }
    
    /**
     * Handle logout
     */
    public function logout()
    {
        $request = new Request();
        
        // Only validate CSRF for POST requests
        if ($request->getMethod() === 'POST') {
            if (!$this->validateCsrf()) {
                return;
            }
        }
        
        Auth::logout();
        $this->setFlashMessage('You have been logged out successfully.', 'info');
        $this->redirect('/login');
    }
    
    /**
     * Validate login input
     */
    private function validateLoginInput($email, $password)
    {
        $errors = [];
        
        if (empty($email)) {
            $errors['email'] = 'Email is required.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Please enter a valid email address.';
        }
        
        if (empty($password)) {
            $errors['password'] = 'Password is required.';
        } elseif (strlen($password) < 6) {
            $errors['password'] = 'Password must be at least 6 characters long.';
        }
        
        return $errors;
    }
    
    /**
     * Redirect to appropriate dashboard based on role
     */
    private function redirectToDashboard()
    {
        $user = Auth::user();
        
        switch ($user['role']) {
            case 'admin':
                $this->redirect('/admin');
                break;
            case 'salarie':
                $this->redirect('/salarie/ideas');
                break;
            case 'evaluateur':
                $this->redirect('/evaluateur/dashboard');
                break;
            default:
                $this->redirect('/');
                break;
        }
    }
}
