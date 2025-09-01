<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\View;
use App\Models\Theme;

class ThemeController
{
    private View $view;
    private Theme $themeModel;
    
    public function __construct()
    {
        $this->view = new View();
        $this->themeModel = new Theme();
        
        // Ensure user is authenticated and is admin
        if (!Auth::isAuthenticated() || !Auth::hasRole('admin')) {
            header('Location: /login');
            exit;
        }
    }
    
    public function index(): void
    {
        $search = $_GET['search'] ?? '';
        $status = $_GET['status'] ?? '';
        $page = (int)($_GET['page'] ?? 1);
        $perPage = 12;

        try {
            $themes = $this->themeModel->getAllPaginated($page, $perPage, $search, $status);
            $totalThemes = $this->themeModel->countAll($search, $status);
            $activeThemes = $this->themeModel->countActive();
            $totalPages = ceil($totalThemes / $perPage);
        } catch (Exception $e) {
            // Fallback to simple data if there's an error
            $themes = [];
            $totalThemes = 0;
            $activeThemes = 0;
            $totalPages = 1;
        }

        $data = [
            'pageTitle' => 'Gestion des Thématiques',
            'themes' => $themes,
            'search' => $search,
            'status' => $status,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalThemes' => $totalThemes,
            'activeThemes' => $activeThemes
        ];

        $this->view->render('admin/themes/index', $data, 'admin');
    }
    
    public function create(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->store();
            return;
        }
        
        $data = [
            'title' => 'Créer une Thématique',
            'user' => Auth::user()
        ];
        
        $this->view->render('admin/themes/create', $data, 'admin');
    }
    
    public function store(): void
    {
        // Get CSRF token name from config
        $config = require dirname(__DIR__, 3) . '/config/config.php';
        $tokenName = $config['security']['csrf_token_name'];
        
        // Validate CSRF token
        if (!Auth::validateCsrfToken($_POST[$tokenName] ?? '')) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Token CSRF invalide'];
            header('Location: /admin/themes/create');
            exit;
        }
        
        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        ];
        
        // Validate required fields
        $errors = $this->validateThemeData($data);
        
        if (!empty($errors)) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => implode('<br>', $errors)];
            $_SESSION['old_input'] = $data;
            header('Location: /admin/themes/create');
            exit;
        }
        
        // Check if theme name already exists
        if ($this->themeModel->findByName($data['name'])) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Cette thématique existe déjà'];
            $_SESSION['old_input'] = $data;
            header('Location: /admin/themes/create');
            exit;
        }
        
        if ($this->themeModel->create($data)) {
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Thématique créée avec succès'];
            header('Location: /admin/themes');
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Erreur lors de la création de la thématique'];
            header('Location: /admin/themes/create');
        }
        exit;
    }
    
    public function edit(int $id): void
    {
        $theme = $this->themeModel->findById($id);
        if (!$theme) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Thématique non trouvée'];
            header('Location: /admin/themes');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->update($id);
            return;
        }
        
        $data = [
            'title' => 'Modifier la Thématique',
            'theme' => $theme,
            'user' => Auth::user()
        ];
        
        $this->view->render('admin/themes/edit', $data, 'admin');
    }
    
    public function update(int $id): void
    {
        $theme = $this->themeModel->findById($id);
        if (!$theme) {
            if ($this->isAjaxRequest()) {
                $this->jsonResponse(['success' => false, 'message' => 'Thématique non trouvée']);
                return;
            }
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Thématique non trouvée'];
            header('Location: /admin/themes');
            exit;
        }
        
        // Get CSRF token name from config
        $config = require dirname(__DIR__, 3) . '/config/config.php';
        $tokenName = $config['security']['csrf_token_name'];
        
        // Validate CSRF token
        if (!Auth::validateCsrfToken($_POST[$tokenName] ?? '')) {
            if ($this->isAjaxRequest()) {
                $this->jsonResponse(['success' => false, 'message' => 'Token CSRF invalide']);
                return;
            }
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Token CSRF invalide'];
            header("Location: /admin/themes/{$id}/edit");
            exit;
        }
        
        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        ];
        
        // Validate required fields
        $errors = $this->validateThemeData($data);
        
        if (!empty($errors)) {
            $errorMessage = implode('<br>', $errors);
            if ($this->isAjaxRequest()) {
                $this->jsonResponse(['success' => false, 'message' => $errorMessage]);
                return;
            }
            $_SESSION['flash'] = ['type' => 'error', 'message' => $errorMessage];
            header("Location: /admin/themes/{$id}/edit");
            exit;
        }
        
        // Check if theme name already exists (excluding current theme)
        $existingTheme = $this->themeModel->findByName($data['name']);
        if ($existingTheme && $existingTheme['id'] !== $id) {
            if ($this->isAjaxRequest()) {
                $this->jsonResponse(['success' => false, 'message' => 'Cette thématique existe déjà']);
                return;
            }
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Cette thématique existe déjà'];
            header("Location: /admin/themes/{$id}/edit");
            exit;
        }
        
        if ($this->themeModel->update($id, $data)) {
            if ($this->isAjaxRequest()) {
                $this->jsonResponse([
                    'success' => true, 
                    'message' => 'Thématique modifiée avec succès',
                    'theme' => array_merge($theme, $data)
                ]);
                return;
            }
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Thématique modifiée avec succès'];
            header('Location: /admin/themes');
        } else {
            if ($this->isAjaxRequest()) {
                $this->jsonResponse(['success' => false, 'message' => 'Erreur lors de la modification de la thématique']);
                return;
            }
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Erreur lors de la modification de la thématique'];
            header("Location: /admin/themes/{$id}/edit");
        }
        exit;
    }
    
    public function delete(int $id): void
    {
        // Get CSRF token name from config
        $config = require dirname(__DIR__, 3) . '/config/config.php';
        $tokenName = $config['security']['csrf_token_name'];
        
        // Validate CSRF token
        if (!Auth::validateCsrfToken($_POST[$tokenName] ?? '')) {
            if ($this->isAjaxRequest()) {
                $this->jsonResponse(['success' => false, 'message' => 'Token CSRF invalide']);
                return;
            }
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Token CSRF invalide'];
            header('Location: /admin/themes');
            exit;
        }
        
        $theme = $this->themeModel->findById($id);
        if (!$theme) {
            if ($this->isAjaxRequest()) {
                $this->jsonResponse(['success' => false, 'message' => 'Thématique non trouvée']);
                return;
            }
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Thématique non trouvée'];
            header('Location: /admin/themes');
            exit;
        }
        
        // Check if theme has associated ideas
        if ($this->themeModel->hasIdeas($id)) {
            $message = 'Impossible de supprimer une thématique qui contient des idées';
            if ($this->isAjaxRequest()) {
                $this->jsonResponse(['success' => false, 'message' => $message]);
                return;
            }
            $_SESSION['flash'] = ['type' => 'error', 'message' => $message];
            header('Location: /admin/themes');
            exit;
        }
        
        if ($this->themeModel->delete($id)) {
            if ($this->isAjaxRequest()) {
                $this->jsonResponse(['success' => true, 'message' => 'Thématique supprimée avec succès']);
                return;
            }
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Thématique supprimée avec succès'];
        } else {
            if ($this->isAjaxRequest()) {
                $this->jsonResponse(['success' => false, 'message' => 'Erreur lors de la suppression de la thématique']);
                return;
            }
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Erreur lors de la suppression de la thématique'];
        }
        
        header('Location: /admin/themes');
        exit;
    }
    
    public function toggle(int $id): void
    {
        // Get CSRF token name from config
        $config = require dirname(__DIR__, 3) . '/config/config.php';
        $tokenName = $config['security']['csrf_token_name'];
        
        // Validate CSRF token
        if (!Auth::validateCsrfToken($_POST[$tokenName] ?? '')) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Token CSRF invalide'];
            header('Location: /admin/themes');
            exit;
        }
        
        $theme = $this->themeModel->findById($id);
        if (!$theme) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Thématique non trouvée'];
            header('Location: /admin/themes');
            exit;
        }
        
        $newStatus = $theme['is_active'] ? 0 : 1;
        $statusText = $newStatus ? 'activée' : 'désactivée';
        
        if ($this->themeModel->update($id, ['is_active' => $newStatus])) {
            $_SESSION['flash'] = ['type' => 'success', 'message' => "Thématique {$statusText} avec succès"];
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Erreur lors du changement de statut'];
        }
        
        header('Location: /admin/themes');
        exit;
    }
    
    private function validateThemeData(array $data): array
    {
        $errors = [];
        
        if (empty($data['name'])) {
            $errors[] = 'Le nom de la thématique est requis';
        } elseif (strlen($data['name']) < 3) {
            $errors[] = 'Le nom doit contenir au moins 3 caractères';
        } elseif (strlen($data['name']) > 100) {
            $errors[] = 'Le nom ne peut pas dépasser 100 caractères';
        }
        
        if (empty($data['description'])) {
            $errors[] = 'La description est requise';
        } elseif (strlen($data['description']) < 10) {
            $errors[] = 'La description doit contenir au moins 10 caractères';
        }
        
        return $errors;
    }
    
    /**
     * Check if request is AJAX
     */
    private function isAjaxRequest(): bool
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
    
    /**
     * Send JSON response
     */
    private function jsonResponse(array $data): void
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
