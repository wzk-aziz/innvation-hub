<?php

/**
 * Helper functions for the application
 */

if (!function_exists('csrf_field')) {
    /**
     * Generate CSRF token field for forms
     * 
     * @return string
     */
    function csrf_field(): string
    {
        return \App\Core\Auth::csrfField();
    }
}

if (!function_exists('csrf_token')) {
    /**
     * Get CSRF token
     * 
     * @return string
     */
    function csrf_token(): string
    {
        return \App\Core\Auth::getCsrfToken();
    }
}

if (!function_exists('old')) {
    /**
     * Get old input value
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function old(string $key, $default = '')
    {
        return $_SESSION['old_input'][$key] ?? $default;
    }
}

if (!function_exists('session')) {
    /**
     * Get or set session value
     * 
     * @param string|null $key
     * @param mixed $default
     * @return mixed
     */
    function session(?string $key = null, $default = null)
    {
        if ($key === null) {
            return $_SESSION;
        }
        
        return $_SESSION[$key] ?? $default;
    }
}

if (!function_exists('redirect')) {
    /**
     * Redirect to a URL
     * 
     * @param string $url
     * @param int $code
     * @return void
     */
    function redirect(string $url, int $code = 302): void
    {
        header("Location: $url", true, $code);
        exit;
    }
}

if (!function_exists('back')) {
    /**
     * Redirect back to previous page
     * 
     * @return void
     */
    function back(): void
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        redirect($referer);
    }
}

if (!function_exists('asset')) {
    /**
     * Generate asset URL
     * 
     * @param string $path
     * @return string
     */
    function asset(string $path): string
    {
        return '/' . ltrim($path, '/');
    }
}

if (!function_exists('url')) {
    /**
     * Generate URL
     * 
     * @param string $path
     * @return string
     */
    function url(string $path): string
    {
        $baseUrl = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'];
        return $baseUrl . '/' . ltrim($path, '/');
    }
}

if (!function_exists('config')) {
    /**
     * Get configuration value
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function config(string $key, $default = null)
    {
        static $config = null;
        
        if ($config === null) {
            $configFile = __DIR__ . '/../config/app.php';
            $config = file_exists($configFile) ? require $configFile : [];
        }
        
        $keys = explode('.', $key);
        $value = $config;
        
        foreach ($keys as $k) {
            if (!isset($value[$k])) {
                return $default;
            }
            $value = $value[$k];
        }
        
        return $value;
    }
}

if (!function_exists('env')) {
    /**
     * Get environment variable
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function env(string $key, $default = null)
    {
        $value = $_ENV[$key] ?? getenv($key);
        
        if ($value === false) {
            return $default;
        }
        
        // Convert string booleans
        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'null':
            case '(null)':
                return null;
        }
        
        return $value;
    }
}

if (!function_exists('dd')) {
    /**
     * Dump and die
     * 
     * @param mixed ...$vars
     * @return void
     */
    function dd(...$vars): void
    {
        foreach ($vars as $var) {
            echo '<pre>';
            var_dump($var);
            echo '</pre>';
        }
        die();
    }
}

if (!function_exists('dump')) {
    /**
     * Dump variable
     * 
     * @param mixed $var
     * @return void
     */
    function dump($var): void
    {
        echo '<pre>';
        var_dump($var);
        echo '</pre>';
    }
}
