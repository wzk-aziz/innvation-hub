<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Application Bootstrap
 * Initializes the application and handles the request lifecycle
 */
class Bootstrap
{
    private array $config;
    private Router $router;
    
    public function __construct()
    {
        $this->config = require dirname(__DIR__, 2) . '/config/config.php';
        $this->initializeApp();
        $this->loadHelpers();
        $this->router = new Router();
    }
    
    public function run(): void
    {
        try {
            // Set timezone
            date_default_timezone_set($this->config['app']['timezone']);
            
            // Start session with secure settings
            $this->startSession();
            
            // Initialize CSRF protection
            Auth::initCsrfToken();
            
            // Route the request
            $this->router->dispatch();
            
        } catch (\Throwable $e) {
            $this->handleException($e);
        }
    }
    
    private function initializeApp(): void
    {
        // Set error reporting based on environment
        if ($this->config['app']['debug']) {
            error_reporting(E_ALL);
            ini_set('display_errors', '1');
        } else {
            error_reporting(0);
            ini_set('display_errors', '0');
        }
        
        // Set memory limit
        ini_set('memory_limit', '256M');
        
        // Set upload limits
        ini_set('upload_max_filesize', (string)$this->config['upload']['max_size']);
        ini_set('post_max_size', (string)$this->config['upload']['max_size']);
    }
    
    private function startSession(): void
    {
        $sessionConfig = $this->config['session'];
        
        // Only configure session settings if session is not already started
        if (session_status() === PHP_SESSION_NONE) {
            // Configure session settings for security
            ini_set('session.name', $sessionConfig['name']);
            ini_set('session.gc_maxlifetime', (string)$sessionConfig['lifetime']);
            ini_set('session.cookie_lifetime', (string)$sessionConfig['lifetime']);
            ini_set('session.cookie_path', $sessionConfig['path']);
            ini_set('session.cookie_domain', $sessionConfig['domain']);
            ini_set('session.cookie_secure', $sessionConfig['secure'] ? '1' : '0');
            ini_set('session.cookie_httponly', $sessionConfig['httponly'] ? '1' : '0');
            ini_set('session.cookie_samesite', $sessionConfig['samesite']);
            ini_set('session.use_strict_mode', '1');
            ini_set('session.use_only_cookies', '1');
            
            session_start();
        }
    }
    
    /**
     * Load helper functions
     */
    private function loadHelpers(): void
    {
        $helpersFile = dirname(__DIR__) . '/helpers.php';
        if (file_exists($helpersFile)) {
            require_once $helpersFile;
        }
    }
    
    private function handleException(\Throwable $e): void
    {
        if ($this->config['app']['debug']) {
            // Show detailed error in development
            echo "<h1>Application Error</h1>";
            echo "<p><strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
            echo "<p><strong>File:</strong> " . htmlspecialchars($e->getFile()) . "</p>";
            echo "<p><strong>Line:</strong> " . $e->getLine() . "</p>";
            echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
        } else {
            // Show generic error in production
            http_response_code(500);
            $view = new View();
            $view->render('errors/500', [], 'front');
        }
    }
    
    public function getConfig(): array
    {
        return $this->config;
    }
}
