<?php

namespace App\Middlewares;

use App\Core\Auth;
use App\Core\Response;
use App\Core\Request;

/**
 * CSRF Middleware
 * Protects against Cross-Site Request Forgery attacks
 */
class CsrfMiddleware
{
    private $config;
    
    public function __construct()
    {
        $this->config = require dirname(__DIR__, 2) . '/config/config.php';
    }
    
    public function handle()
    {
        $request = new Request();
        
        // Skip CSRF check for GET, HEAD, OPTIONS requests
        if (in_array($request->getMethod(), ['GET', 'HEAD', 'OPTIONS'])) {
            return true;
        }
        
        // Get CSRF token from request
        $tokenName = $this->config['security']['csrf_token_name'];
        $token = $request->input($tokenName);
        
        // Validate CSRF token
        if (!Auth::validateCsrfToken($token)) {
            if ($request->isAjax()) {
                Response::json(['error' => 'CSRF token mismatch'], 419);
            } else {
                Response::forbidden('CSRF token mismatch. Please refresh the page and try again.');
            }
            return false;
        }
        
        return true;
    }
}
