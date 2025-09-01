<?php

namespace App\Core;

/**
 * View Class
 * Handles view rendering and template management
 */
class View
{
    private $config;
    private $viewsPath;
    
    public function __construct()
    {
        $this->config = require dirname(__DIR__, 2) . '/config/config.php';
        $this->viewsPath = $this->config['paths']['views'];
    }
    
    /**
     * Render a view with layout
     */
    public function render($template, $data = [], $layout = 'front')
    {
        // Extract data to variables
        extract($data);
        
        // Add common variables
        $config = $this->config;
        $authUser = Auth::user(); // Changed from $user to $authUser to avoid conflicts
        $flashMessages = $this->getFlashMessages();
        $csrfToken = Auth::getCsrfToken();
        $csrfTokenName = $config['security']['csrf_token_name'];
        
        // Start output buffering
        ob_start();
        
        // Include the view file
        $viewFile = $this->viewsPath . '/' . $template . '.php';
        
        if (!file_exists($viewFile)) {
            throw new \Exception("View file not found: {$viewFile}");
        }
        
        include $viewFile;
        
        // Get the content
        $content = ob_get_clean();
        
        // Include layout if specified
        if ($layout) {
            $layoutFile = $this->viewsPath . '/layouts/' . $layout . '.php';
            
            if (!file_exists($layoutFile)) {
                throw new \Exception("Layout file not found: {$layoutFile}");
            }
            
            include $layoutFile;
        } else {
            echo $content;
        }
    }
    
    /**
     * Render a partial view
     */
    public function partial($template, $data = [])
    {
        extract($data);
        
        $partialFile = $this->viewsPath . '/partials/' . $template . '.php';
        
        if (!file_exists($partialFile)) {
            throw new \Exception("Partial file not found: {$partialFile}");
        }
        
        include $partialFile;
    }
    
    /**
     * Escape output for security
     */
    public function escape($value)
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Generate URL
     */
    public function url($path = '')
    {
        $baseUrl = rtrim($this->config['app']['url'], '/');
        $path = ltrim($path, '/');
        
        return $baseUrl . '/' . $path;
    }
    
    /**
     * Generate asset URL
     */
    public function asset($path)
    {
        return $this->url('assets/' . ltrim($path, '/'));
    }
    
    /**
     * Check if user has role
     */
    public function hasRole($role)
    {
        return Auth::hasRole($role);
    }
    
    /**
     * Format date
     */
    public function formatDate($date, $format = 'Y-m-d H:i:s')
    {
        if (is_string($date)) {
            $date = new \DateTime($date);
        }
        
        return $date->format($format);
    }
    
    /**
     * Get flash messages and clear them
     */
    private function getFlashMessages()
    {
        $messages = $_SESSION['flash_messages'] ?? [];
        unset($_SESSION['flash_messages']);
        return $messages;
    }
    
    /**
     * Include a view file and return its content
     */
    public function include($template, $data = [])
    {
        extract($data);
        
        $viewFile = $this->viewsPath . '/' . $template . '.php';
        
        if (!file_exists($viewFile)) {
            throw new \Exception("View file not found: {$viewFile}");
        }
        
        ob_start();
        include $viewFile;
        return ob_get_clean();
    }
}
