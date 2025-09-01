<?php

namespace App\Core;

/**
 * Request Class
 * Handles HTTP request data and methods
 */
class Request
{
    private $method;
    private $uri;
    private $data;
    
    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->uri = $this->parseUri();
        $this->data = $this->parseData();
    }
    
    /**
     * Get HTTP method
     */
    public function getMethod()
    {
        // Handle method override for PUT/DELETE
        if ($this->method === 'POST' && isset($this->data['_method'])) {
            return strtoupper($this->data['_method']);
        }
        
        return $this->method;
    }
    
    /**
     * Get request URI
     */
    public function getUri()
    {
        return $this->uri;
    }
    
    /**
     * Get all input data
     */
    public function all()
    {
        return $this->data;
    }
    
    /**
     * Get specific input value
     */
    public function input($key, $default = null)
    {
        return $this->data[$key] ?? $default;
    }
    
    /**
     * Check if input exists
     */
    public function has($key)
    {
        return isset($this->data[$key]);
    }
    
    /**
     * Get only specified keys
     */
    public function only($keys)
    {
        $result = [];
        foreach ($keys as $key) {
            if (isset($this->data[$key])) {
                $result[$key] = $this->data[$key];
            }
        }
        return $result;
    }
    
    /**
     * Get all except specified keys
     */
    public function except($keys)
    {
        $result = $this->data;
        foreach ($keys as $key) {
            unset($result[$key]);
        }
        return $result;
    }
    
    /**
     * Check if request is AJAX
     */
    public function isAjax()
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
    
    /**
     * Check if request is POST
     */
    public function isPost()
    {
        return $this->method === 'POST';
    }
    
    /**
     * Check if request is GET
     */
    public function isGet()
    {
        return $this->method === 'GET';
    }
    
    /**
     * Get file upload
     */
    public function file($key)
    {
        return $_FILES[$key] ?? null;
    }
    
    /**
     * Get server variable
     */
    public function server($key, $default = null)
    {
        return $_SERVER[$key] ?? $default;
    }
    
    /**
     * Get header value
     */
    public function header($key, $default = null)
    {
        $key = 'HTTP_' . strtoupper(str_replace('-', '_', $key));
        return $_SERVER[$key] ?? $default;
    }
    
    /**
     * Get client IP address
     */
    public function getClientIp()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            return $_SERVER['REMOTE_ADDR'];
        }
    }
    
    /**
     * Get user agent
     */
    public function getUserAgent()
    {
        return $_SERVER['HTTP_USER_AGENT'] ?? '';
    }
    
    /**
     * Parse URI from request
     */
    private function parseUri()
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        
        // Remove script name if present (for cases where URL rewriting isn't working)
        if (strpos($uri, $_SERVER['SCRIPT_NAME']) === 0) {
            $uri = substr($uri, strlen($_SERVER['SCRIPT_NAME']));
        }
        
        // Remove query string
        $uri = strtok($uri, '?');
        
        // Ensure leading slash
        if (substr($uri, 0, 1) !== '/') {
            $uri = '/' . $uri;
        }
        
        return $uri;
    }
    
    /**
     * Parse request data from various sources
     */
    private function parseData()
    {
        $data = [];
        
        // GET parameters
        if (!empty($_GET)) {
            $data = array_merge($data, $_GET);
        }
        
        // POST parameters
        if (!empty($_POST)) {
            $data = array_merge($data, $_POST);
        }
        
        // JSON input for API requests
        if (in_array($this->method, ['POST', 'PUT', 'PATCH']) && 
            strpos($this->header('Content-Type', ''), 'application/json') !== false) {
            
            $input = file_get_contents('php://input');
            $json = json_decode($input, true);
            
            if (json_last_error() === JSON_ERROR_NONE && is_array($json)) {
                $data = array_merge($data, $json);
            }
        }
        
        // Sanitize input data
        return $this->sanitizeData($data);
    }
    
    /**
     * Sanitize input data
     */
    private function sanitizeData($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->sanitizeData($value);
            }
        } else {
            // Basic sanitization - trim whitespace
            $data = trim($data);
        }
        
        return $data;
    }
}
