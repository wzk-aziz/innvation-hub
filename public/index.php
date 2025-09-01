<?php

declare(strict_types=1);

/**
 * Front Controller - Entry point for all requests
 * Bootstraps the application and handles routing
 */

// Define application root path
define('APP_ROOT', dirname(__DIR__));

// Composer autoloader
if (!file_exists(APP_ROOT . '/vendor/autoload.php')) {
    // Generate vendor directory if not exists
    if (!is_dir(APP_ROOT . '/vendor')) {
        mkdir(APP_ROOT . '/vendor', 0755, true);
    }
    
    // Create minimal autoloader
    spl_autoload_register(function (string $class): void {
        $prefix = 'App\\';
        $base_dir = APP_ROOT . '/app/';
        
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            return;
        }
        
        $relative_class = substr($class, $len);
        $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
        
        if (file_exists($file)) {
            require $file;
        }
    });
} else {
    require_once APP_ROOT . '/vendor/autoload.php';
}

use App\Core\Bootstrap;

// Start the application
try {
    $app = new Bootstrap();
    $app->run();
} catch (Throwable $e) {
    // Error handling
    http_response_code(500);
    
    $config = require APP_ROOT . '/config/config.php';
    
    if ($config['app']['debug']) {
        echo "<h1>Application Error</h1>";
        echo "<p><strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
        echo "<p><strong>File:</strong> " . htmlspecialchars($e->getFile()) . "</p>";
        echo "<p><strong>Line:</strong> " . $e->getLine() . "</p>";
        echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    } else {
        echo "<h1>500 - Internal Server Error</h1>";
        echo "<p>Something went wrong. Please try again later.</p>";
    }
}
