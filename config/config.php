<?php

/**
 * Application Configuration
 * Reads environment variables and returns configuration array
 */

// Simple environment loader - only load once
if (!defined('ENV_LOADED')) {
    define('ENV_LOADED', true);
    
    $envPath = dirname(__DIR__) . '/.env';
    if (!file_exists($envPath)) {
        $envPath = dirname(__DIR__) . '/.env.example';
    }
    
    if (file_exists($envPath)) {
        $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) {
                continue;
            }
            
            if (strpos($line, '=') !== false) {
                list($name, $value) = explode('=', $line, 2);
                $name = trim($name);
                $value = trim($value);
                
                if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
                    putenv(sprintf('%s=%s', $name, $value));
                    $_ENV[$name] = $value;
                    $_SERVER[$name] = $value;
                }
            }
        }
    }
}

return [
    // Database Configuration
    'database' => [
        'host' => $_ENV['DB_HOST'] ?? 'localhost',
        'dbname' => $_ENV['DB_NAME'] ?? 'company_ideas',
        'username' => $_ENV['DB_USER'] ?? 'root',
        'password' => $_ENV['DB_PASS'] ?? '',
        'charset' => 'utf8mb4',
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    ],
    
    // Application Configuration
    'app' => [
        'name' => $_ENV['APP_NAME'] ?? 'Company Ideas Management',
        'env' => $_ENV['APP_ENV'] ?? 'development',
        'debug' => filter_var($_ENV['APP_DEBUG'] ?? true, FILTER_VALIDATE_BOOLEAN),
        'url' => rtrim($_ENV['APP_URL'] ?? 'http://localhost', '/'),
        'key' => $_ENV['APP_KEY'] ?? 'change-this-key-in-production',
        'timezone' => 'Europe/Paris',
    ],
    
    // Security Configuration
    'security' => [
        'csrf_token_name' => $_ENV['CSRF_TOKEN_NAME'] ?? 'csrf_token',
        'password_cost' => 12,
        'login_attempts_max' => 5,
        'login_attempts_window' => 900, // 15 minutes
    ],
    
    // Session Configuration
    'session' => [
        'name' => $_ENV['SESSION_NAME'] ?? 'company_ideas_session',
        'lifetime' => (int)($_ENV['SESSION_LIFETIME'] ?? 7200),
        'path' => '/',
        'domain' => '',
        'secure' => false, // Set to true in production with HTTPS
        'httponly' => true,
        'samesite' => 'Lax',
    ],
    
    // Upload Configuration
    'upload' => [
        'max_size' => (int)($_ENV['UPLOAD_MAX_SIZE'] ?? 5242880), // 5MB
        'allowed_extensions' => explode(',', $_ENV['ALLOWED_EXTENSIONS'] ?? 'pdf,doc,docx,jpg,jpeg,png,gif'),
        'upload_path' => dirname(__DIR__) . '/public/uploads/',
    ],
    
    // Paths
    'paths' => [
        'root' => dirname(__DIR__),
        'app' => dirname(__DIR__) . '/app',
        'views' => dirname(__DIR__) . '/app/Views',
        'public' => dirname(__DIR__) . '/public',
        'uploads' => dirname(__DIR__) . '/public/uploads',
    ],
];
