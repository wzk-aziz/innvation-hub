<?php

namespace App\Middlewares;

use App\Core\Auth;
use App\Core\Response;

/**
 * Role Middleware
 * Ensures user has required role to access route
 */
class RoleMiddleware
{
    public function handle($requiredRole)
    {
        if (!Auth::check()) {
            Response::redirect('/login');
            return false;
        }
        
        if (!Auth::hasRole($requiredRole)) {
            Response::forbidden('You do not have permission to access this page.');
            return false;
        }
        
        return true;
    }
    
    /**
     * Check if user has any of the specified roles
     */
    public function handleMultiple($requiredRoles)
    {
        if (!Auth::check()) {
            Response::redirect('/login');
            return false;
        }
        
        if (!Auth::hasAnyRole($requiredRoles)) {
            Response::forbidden('You do not have permission to access this page.');
            return false;
        }
        
        return true;
    }
}
