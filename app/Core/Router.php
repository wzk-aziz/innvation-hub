<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Router Class
 * Handles URL routing and dispatches requests to appropriate controllers
 */
class Router
{
    private $routes = [];
    private $config;
    
    public function __construct()
    {
        $this->config = require dirname(__DIR__, 2) . '/config/config.php';
        $this->defineRoutes();
    }
    
    private function defineRoutes()
    {
        // Authentication routes
        $this->routes = [
            // Auth routes
            'GET|/' => ['controller' => 'HomeController', 'method' => 'index'],
            'GET|/login' => ['controller' => 'AuthController', 'method' => 'showLogin'],
            'POST|/login' => ['controller' => 'AuthController', 'method' => 'login'],
            'GET|/logout' => ['controller' => 'AuthController', 'method' => 'logout'],
            'POST|/logout' => ['controller' => 'AuthController', 'method' => 'logout'],
            
            // Admin routes - Dashboard
            'GET|/admin' => ['controller' => 'Admin\\DashboardController', 'method' => 'index', 'role' => 'admin'],
            
            // Admin routes - Users
            'GET|/admin/users' => ['controller' => 'Admin\\UserController', 'method' => 'index', 'role' => 'admin'],
            'GET|/admin/users/create' => ['controller' => 'Admin\\UserController', 'method' => 'create', 'role' => 'admin'],
            'POST|/admin/users' => ['controller' => 'Admin\\UserController', 'method' => 'store', 'role' => 'admin'],
            'GET|/admin/users/{id}' => ['controller' => 'Admin\\UserController', 'method' => 'show', 'role' => 'admin'],
            'GET|/admin/users/{id}/edit' => ['controller' => 'Admin\\UserController', 'method' => 'edit', 'role' => 'admin'],
            'POST|/admin/users/{id}' => ['controller' => 'Admin\\UserController', 'method' => 'update', 'role' => 'admin'],
            'POST|/admin/users/{id}/delete' => ['controller' => 'Admin\\UserController', 'method' => 'delete', 'role' => 'admin'],
            
            // Admin routes - Themes
            'GET|/admin/themes' => ['controller' => 'Admin\\ThemeController', 'method' => 'index', 'role' => 'admin'],
            'GET|/admin/themes/create' => ['controller' => 'Admin\\ThemeController', 'method' => 'create', 'role' => 'admin'],
            'POST|/admin/themes' => ['controller' => 'Admin\\ThemeController', 'method' => 'store', 'role' => 'admin'],
            'GET|/admin/themes/{id}/edit' => ['controller' => 'Admin\\ThemeController', 'method' => 'edit', 'role' => 'admin'],
            'POST|/admin/themes/{id}' => ['controller' => 'Admin\\ThemeController', 'method' => 'update', 'role' => 'admin'],
            'POST|/admin/themes/{id}/delete' => ['controller' => 'Admin\\ThemeController', 'method' => 'delete', 'role' => 'admin'],
            'POST|/admin/themes/{id}/toggle' => ['controller' => 'Admin\\ThemeController', 'method' => 'toggle', 'role' => 'admin'],
            
            // Admin routes - Ideas supervision
            'GET|/admin/ideas' => ['controller' => 'Admin\\IdeaController', 'method' => 'index', 'role' => 'admin'],
            'GET|/admin/ideas/{id}' => ['controller' => 'Admin\\IdeaController', 'method' => 'show', 'role' => 'admin'],
            'POST|/admin/ideas/{id}/status' => ['controller' => 'Admin\\IdeaController', 'method' => 'updateStatus', 'role' => 'admin'],
            'POST|/admin/ideas/{id}/feedback' => ['controller' => 'Admin\\IdeaController', 'method' => 'addFeedback', 'role' => 'admin'],
            
            // Admin routes - Reports
            'GET|/admin/reports' => ['controller' => 'Admin\\ReportController', 'method' => 'index', 'role' => 'admin'],
            'GET|/admin/reports/users' => ['controller' => 'Admin\\ReportController', 'method' => 'users', 'role' => 'admin'],
            'GET|/admin/reports/ideas' => ['controller' => 'Admin\\ReportController', 'method' => 'ideas', 'role' => 'admin'],
            'GET|/admin/reports/themes' => ['controller' => 'Admin\\ReportController', 'method' => 'themes', 'role' => 'admin'],
            'GET|/admin/reports/export' => ['controller' => 'Admin\\ReportController', 'method' => 'export', 'role' => 'admin'],
            
            // Admin routes - Settings
            'GET|/admin/settings' => ['controller' => 'Admin\\SettingController', 'method' => 'index', 'role' => 'admin'],
            'GET|/admin/settings/general' => ['controller' => 'Admin\\SettingController', 'method' => 'general', 'role' => 'admin'],
            'POST|/admin/settings/general' => ['controller' => 'Admin\\SettingController', 'method' => 'general', 'role' => 'admin'],
            'GET|/admin/settings/security' => ['controller' => 'Admin\\SettingController', 'method' => 'security', 'role' => 'admin'],
            'POST|/admin/settings/security' => ['controller' => 'Admin\\SettingController', 'method' => 'security', 'role' => 'admin'],
            'GET|/admin/settings/email' => ['controller' => 'Admin\\SettingController', 'method' => 'email', 'role' => 'admin'],
            'POST|/admin/settings/email' => ['controller' => 'Admin\\SettingController', 'method' => 'email', 'role' => 'admin'],
            'GET|/admin/settings/backup' => ['controller' => 'Admin\\SettingController', 'method' => 'backup', 'role' => 'admin'],
            'POST|/admin/settings/backup/create' => ['controller' => 'Admin\\SettingController', 'method' => 'createBackup', 'role' => 'admin'],
            
            // Salarie routes - Ideas management
            'GET|/salarie/ideas' => ['controller' => 'Salarie\\IdeaController', 'method' => 'index', 'role' => 'salarie'],
            'GET|/salarie/ideas/create' => ['controller' => 'Salarie\\IdeaController', 'method' => 'create', 'role' => 'salarie'],
            'POST|/salarie/ideas' => ['controller' => 'Salarie\\IdeaController', 'method' => 'store', 'role' => 'salarie'],
            'GET|/salarie/ideas/{id}' => ['controller' => 'Salarie\\IdeaController', 'method' => 'show', 'role' => 'salarie'],
            'GET|/salarie/ideas/{id}/edit' => ['controller' => 'Salarie\\IdeaController', 'method' => 'edit', 'role' => 'salarie'],
            'GET|/salarie/ideas/{id}/pdf' => ['controller' => 'Salarie\\IdeaController', 'method' => 'downloadPdf', 'role' => 'salarie'],
            'POST|/salarie/ideas/{id}' => ['controller' => 'Salarie\\IdeaController', 'method' => 'update', 'role' => 'salarie'],
            'PUT|/salarie/ideas/{id}' => ['controller' => 'Salarie\\IdeaController', 'method' => 'update', 'role' => 'salarie'],
            'POST|/salarie/ideas/{id}/delete' => ['controller' => 'Salarie\\IdeaController', 'method' => 'delete', 'role' => 'salarie'],
            
            // Evaluateur routes - Dashboard and evaluation system
            'GET|/evaluateur' => ['controller' => 'Evaluateur\\ReviewController', 'method' => 'index', 'role' => 'evaluateur'],
            'GET|/evaluateur/dashboard' => ['controller' => 'Evaluateur\\ReviewController', 'method' => 'index', 'role' => 'evaluateur'],
            
            // Evaluateur routes - Review and evaluation
            'GET|/evaluateur/review' => ['controller' => 'Evaluateur\\ReviewController', 'method' => 'review', 'role' => 'evaluateur'],
            'GET|/evaluateur/review/{id}' => ['controller' => 'Evaluateur\\ReviewController', 'method' => 'show', 'role' => 'evaluateur'],
            'POST|/evaluateur/evaluate' => ['controller' => 'Evaluateur\\ReviewController', 'method' => 'evaluate', 'role' => 'evaluateur'],
            
            // Evaluateur routes - Feedback and statistics
            'GET|/evaluateur/feedback/{id}' => ['controller' => 'Evaluateur\\ReviewController', 'method' => 'feedback', 'role' => 'evaluateur'],
            'POST|/evaluateur/feedback/{id}' => ['controller' => 'Evaluateur\\ReviewController', 'method' => 'feedback', 'role' => 'evaluateur'],
            'GET|/evaluateur/my-evaluations' => ['controller' => 'Evaluateur\\ReviewController', 'method' => 'myEvaluations', 'role' => 'evaluateur'],
            'GET|/evaluateur/statistics' => ['controller' => 'Evaluateur\\ReviewController', 'method' => 'statistics', 'role' => 'evaluateur'],
            'GET|/evaluateur/best-ideas' => ['controller' => 'Evaluateur\\ReviewController', 'method' => 'bestIdeas', 'role' => 'evaluateur'],
        ];
    }
    
    public function dispatch()
    {
        $request = new Request();
        $method = $request->getMethod();
        $uri = $request->getUri();
        
        // Remove query string
        $uri = strtok($uri, '?');
        
        // Remove trailing slash except for root
        if ($uri !== '/' && substr($uri, -1) === '/') {
            $uri = rtrim($uri, '/');
        }
        
        $routeKey = $method . '|' . $uri;
        
        // Check for exact match first
        if (isset($this->routes[$routeKey])) {
            $this->handleRoute($this->routes[$routeKey], []);
            return;
        }
        
        // Check for parameterized routes
        foreach ($this->routes as $pattern => $route) {
            if ($this->matchRoute($pattern, $routeKey, $params)) {
                $this->handleRoute($route, $params);
                return;
            }
        }
        
        // Route not found
        $this->handleNotFound();
        $this->handleNotFound();
    }
    
    private function matchRoute($pattern, $routeKey, &$params)
    {
        // Escape the pipe character in the pattern to prevent regex OR interpretation
        $pattern = str_replace('|', '\|', $pattern);
        $pattern = str_replace('{id}', '(\d+)', $pattern);
        $pattern = str_replace('{slug}', '([a-zA-Z0-9-_]+)', $pattern);
        $regexPattern = '#^' . $pattern . '$#';
        
        if (preg_match($regexPattern, $routeKey, $matches)) {
            array_shift($matches); // Remove full match
            $params = $matches;
            return true;
        }
        
        return false;
    }
    
    private function handleRoute($route, $params)
    {
        // Check authentication if required
        if (isset($route['role'])) {
            if (!Auth::check()) {
                Response::redirect('/login');
                return;
            }
            
            if (!Auth::hasRole($route['role'])) {
                Response::forbidden();
                return;
            }
        }
        
        $controllerName = 'App\\Controllers\\' . $route['controller'];
        
        if (!class_exists($controllerName)) {
            throw new \Exception("Controller {$controllerName} not found");
        }
        
        $controller = new $controllerName();
        $method = $route['method'];
        
        if (!method_exists($controller, $method)) {
            throw new \Exception("Method {$method} not found in {$controllerName}");
        }
        
        // Call controller method with parameters
        call_user_func_array([$controller, $method], $params);
    }
    
    private function handleNotFound()
    {
        http_response_code(404);
        $view = new View();
        $view->render('errors/404', [], 'front');
    }
}
