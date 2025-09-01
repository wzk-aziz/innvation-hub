<?php

namespace App\Core;

/**
 * Response Class
 * Handles HTTP responses
 */
class Response
{
    /**
     * Send JSON response
     */
    public static function json($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    /**
     * Redirect to URL
     */
    public static function redirect($url, $statusCode = 302)
    {
        http_response_code($statusCode);
        header("Location: {$url}");
        exit;
    }
    
    /**
     * Send 404 Not Found response
     */
    public static function notFound($message = 'Page not found')
    {
        http_response_code(404);
        
        $view = new View();
        $view->render('errors/404', ['message' => $message], 'front');
        exit;
    }
    
    /**
     * Send 403 Forbidden response
     */
    public static function forbidden($message = 'Access denied')
    {
        http_response_code(403);
        
        $view = new View();
        $view->render('errors/403', ['message' => $message], 'front');
        exit;
    }
    
    /**
     * Send 500 Internal Server Error response
     */
    public static function serverError($message = 'Internal server error')
    {
        http_response_code(500);
        
        $view = new View();
        $view->render('errors/500', ['message' => $message], 'front');
        exit;
    }
    
    /**
     * Send 422 Unprocessable Entity response (validation errors)
     */
    public static function unprocessable($errors)
    {
        http_response_code(422);
        
        if (self::wantsJson()) {
            self::json(['errors' => $errors], 422);
        } else {
            // Store errors in session for display
            $_SESSION['validation_errors'] = $errors;
            $_SESSION['old_input'] = $_POST;
            
            // Redirect back
            $referer = $_SERVER['HTTP_REFERER'] ?? '/';
            self::redirect($referer);
        }
    }
    
    /**
     * Check if client wants JSON response
     */
    private static function wantsJson()
    {
        return (isset($_SERVER['HTTP_ACCEPT']) && 
                strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) ||
               (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');
    }
    
    /**
     * Set HTTP header
     */
    public static function header($name, $value)
    {
        header("{$name}: {$value}");
    }
    
    /**
     * Set multiple headers
     */
    public static function headers($headers)
    {
        foreach ($headers as $name => $value) {
            self::header($name, $value);
        }
    }
    
    /**
     * Set status code
     */
    public static function status($code)
    {
        http_response_code($code);
    }
    
    /**
     * Send file download response
     */
    public static function download($filePath, $fileName = null, $deleteAfter = false)
    {
        if (!file_exists($filePath)) {
            self::notFound('File not found');
            return;
        }
        
        $fileName = $fileName ?: basename($filePath);
        $fileSize = filesize($filePath);
        $mimeType = mime_content_type($filePath) ?: 'application/octet-stream';
        
        // Set headers
        self::header('Content-Type', $mimeType);
        self::header('Content-Length', $fileSize);
        self::header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
        self::header('Cache-Control', 'no-cache, no-store, must-revalidate');
        self::header('Pragma', 'no-cache');
        self::header('Expires', '0');
        
        // Clear output buffer
        if (ob_get_level()) {
            ob_end_clean();
        }
        
        // Read and output file
        readfile($filePath);
        
        // Delete file if requested
        if ($deleteAfter) {
            unlink($filePath);
        }
        
        exit;
    }
    
    /**
     * Send file inline response
     */
    public static function file($filePath, $fileName = null)
    {
        if (!file_exists($filePath)) {
            self::notFound('File not found');
            return;
        }
        
        $fileName = $fileName ?: basename($filePath);
        $mimeType = mime_content_type($filePath) ?: 'application/octet-stream';
        
        // Set headers
        self::header('Content-Type', $mimeType);
        self::header('Content-Disposition', 'inline; filename="' . $fileName . '"');
        
        // Clear output buffer
        if (ob_get_level()) {
            ob_end_clean();
        }
        
        // Read and output file
        readfile($filePath);
        exit;
    }
}
