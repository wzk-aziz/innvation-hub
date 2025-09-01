<?php

namespace App\Middlewares;

use App\Core\Auth;
use App\Core\Response;

/**
 * Authentication Middleware
 * Ensures user is authenticated before accessing protected routes
 */
class AuthMiddleware
{
    public function handle()
    {
        if (!Auth::check()) {
            // Store intended URL for redirect after login
            $_SESSION['intended_url'] = $_SERVER['REQUEST_URI'];
            
            Response::redirect('/login');
            return false;
        }
        
        return true;
    }
}
