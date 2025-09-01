<?php

namespace App\Core;

/**
 * Base Controller Class
 * Provides common functionality for all controllers
 */
abstract class Controller
{
    protected $config;
    protected $view;
    
    public function __construct()
    {
        $this->config = require dirname(__DIR__, 2) . '/config/config.php';
        $this->view = new View();
    }
    
    /**
     * Render a view with data
     */
    protected function render($template, $data = [], $layout = null)
    {
        // Determine layout based on user role if not specified
        if ($layout === null) {
            $layout = Auth::hasRole('admin') ? 'admin' : 'front';
        }
        
        $this->view->render($template, $data, $layout);
    }
    
    /**
     * Return JSON response
     */
    protected function json($data, $statusCode = 200)
    {
        Response::json($data, $statusCode);
    }
    
    /**
     * Redirect to a URL
     */
    protected function redirect($url, $statusCode = 302)
    {
        Response::redirect($url, $statusCode);
    }
    
    /**
     * Redirect back with message
     */
    protected function back($message = null, $type = 'info')
    {
        if ($message) {
            $this->setFlashMessage($message, $type);
        }
        
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        $this->redirect($referer);
    }
    
    /**
     * Set flash message
     */
    protected function setFlashMessage($message, $type = 'info')
    {
        if (!isset($_SESSION['flash_messages'])) {
            $_SESSION['flash_messages'] = [];
        }
        
        $_SESSION['flash_messages'][] = [
            'message' => $message,
            'type' => $type
        ];
    }
    
    /**
     * Get and clear flash messages
     */
    protected function getFlashMessages()
    {
        $messages = $_SESSION['flash_messages'] ?? [];
        unset($_SESSION['flash_messages']);
        return $messages;
    }
    
    /**
     * Validate CSRF token
     */
    protected function validateCsrf()
    {
        $request = new Request();
        $token = $request->input($this->config['security']['csrf_token_name']);
        
        if (!Auth::validateCsrfToken($token)) {
            Response::forbidden('Invalid CSRF token');
            return false;
        }
        
        return true;
    }
    
    /**
     * Require authentication
     */
    protected function requireAuth()
    {
        if (!Auth::check()) {
            $this->redirect('/login');
            exit;
        }
    }
    
    /**
     * Require specific role
     */
    protected function requireRole($role)
    {
        $this->requireAuth();
        
        if (!Auth::hasRole($role)) {
            Response::forbidden();
            exit;
        }
    }
    
    /**
     * Get current user
     */
    protected function user()
    {
        return Auth::user();
    }
    
    /**
     * Handle file upload
     */
    protected function handleFileUpload($fieldName, $allowedTypes = null)
    {
        if (!isset($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }
        
        $file = $_FILES[$fieldName];
        
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new \Exception('File upload error: ' . $file['error']);
        }
        
        // Validate file size
        if ($file['size'] > $this->config['upload']['max_size']) {
            throw new \Exception('File size exceeds maximum allowed size');
        }
        
        // Validate file extension
        $allowedTypes = $allowedTypes ?? $this->config['upload']['allowed_extensions'];
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        
        if (!in_array($extension, $allowedTypes)) {
            throw new \Exception('File type not allowed');
        }
        
        // Generate unique filename
        $filename = uniqid() . '_' . time() . '.' . $extension;
        $uploadPath = $this->config['upload']['upload_path'];
        
        // Create upload directory if it doesn't exist
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }
        
        $destination = $uploadPath . $filename;
        
        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            throw new \Exception('Failed to move uploaded file');
        }
        
        return [
            'filename' => $filename,
            'original_name' => $file['name'],
            'size' => $file['size'],
            'type' => $file['type'],
            'path' => 'uploads/' . $filename
        ];
    }
}
