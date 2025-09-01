<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\View;
use App\Models\Idea;
use App\Models\Feedback;

class IdeaController
{
    private View $view;
    private Idea $ideaModel;
    private Feedback $feedbackModel;
    
    public function __construct()
    {
        $this->view = new View();
        $this->ideaModel = new Idea();
        $this->feedbackModel = new Feedback();
        
        // Ensure user is authenticated and is admin
        if (!Auth::isAuthenticated() || !Auth::hasRole('admin')) {
            header('Location: /login');
            exit;
        }
    }
    
    public function index(): void
    {
        try {
            // Get filter parameters
            $search = trim($_GET['search'] ?? '');
            $status = $_GET['status'] ?? '';
            $selectedTheme = $_GET['theme'] ?? '';
            $page = max(1, (int)($_GET['page'] ?? 1));
            $perPage = 12;
            
            // Check if this is an AJAX request
            $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                     strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
            
            // Build filters array
            $filters = [];
            if (!empty($search)) {
                $filters['search'] = $search;
            }
            if (!empty($status)) {
                $filters['status'] = $status;
            }
            if (!empty($selectedTheme)) {
                $filters['theme_id'] = $selectedTheme;
            }
            
            // Get paginated ideas
            $ideas = $this->ideaModel->getAll($page, $perPage, $filters);
            
            // Get total count and calculate pages
            $totalIdeas = $this->ideaModel->count($filters);
            $totalPages = max(1, ceil($totalIdeas / $perPage));
            
            // Get themes for filter dropdown
            $themes = \App\Models\Theme::class;
            $themeModel = new $themes();
            $themesData = $themeModel->getAll();
            
            // Get statistics with current filters
            $stats = [
                'total' => $this->ideaModel->count(),
                'pending' => $this->ideaModel->countByStatus('submitted'),
                'under_review' => $this->ideaModel->countByStatus('under_review'),
                'approved' => $this->ideaModel->countByStatus('accepted'),
                'rejected' => $this->ideaModel->countByStatus('rejected'),
            ];
            
            // Get filtered statistics for current search
            $filteredStats = [
                'total' => $totalIdeas,
                'pending' => !empty($filters) ? $this->getFilteredCountByStatus('submitted', $filters) : $stats['pending'],
                'under_review' => !empty($filters) ? $this->getFilteredCountByStatus('under_review', $filters) : $stats['under_review'],
                'approved' => !empty($filters) ? $this->getFilteredCountByStatus('accepted', $filters) : $stats['approved'],
                'rejected' => !empty($filters) ? $this->getFilteredCountByStatus('rejected', $filters) : $stats['rejected'],
            ];
            
            $data = [
                'title' => 'Supervision des Idées',
                'ideas' => $ideas ?? [],
                'themes' => $themesData ?? [],
                'search' => $search,
                'status' => $status,
                'selectedTheme' => $selectedTheme,
                'currentPage' => $page,
                'totalPages' => $totalPages,
                'totalIdeas' => $totalIdeas,
                'pendingIdeas' => $stats['pending'] ?? 0,
                'underReviewIdeas' => $stats['under_review'] ?? 0,
                'acceptedIdeas' => $stats['approved'] ?? 0,
                'rejectedIdeas' => $stats['rejected'] ?? 0,
                'filteredStats' => $filteredStats,
                'user' => Auth::user()
            ];
            
            // For AJAX requests, return JSON or just render the content
            if ($isAjax) {
                // Set proper content type for AJAX
                header('Content-Type: text/html; charset=utf-8');
            }
            
            $this->view->render('admin/ideas/index', $data, 'admin');
            
        } catch (\Exception $e) {
            error_log("Error in IdeaController::index: " . $e->getMessage());
            
            // Fallback data
            $data = [
                'title' => 'Supervision des Idées',
                'ideas' => [],
                'themes' => [],
                'search' => '',
                'status' => '',
                'selectedTheme' => '',
                'currentPage' => 1,
                'totalPages' => 1,
                'totalIdeas' => 0,
                'pendingIdeas' => 0,
                'underReviewIdeas' => 0,
                'acceptedIdeas' => 0,
                'rejectedIdeas' => 0,
                'filteredStats' => ['total' => 0, 'pending' => 0, 'under_review' => 0, 'approved' => 0, 'rejected' => 0],
                'user' => Auth::user(),
                'error' => 'Erreur lors du chargement des idées'
            ];
            
            $this->view->render('admin/ideas/index', $data, 'admin');
        }
    }
    
    private function getFilteredCountByStatus(string $status, array $filters): int
    {
        try {
            // Add status to filters
            $statusFilters = array_merge($filters, ['status' => $status]);
            return $this->ideaModel->count($statusFilters);
        } catch (\Exception $e) {
            error_log("Error counting filtered ideas by status: " . $e->getMessage());
            return 0;
        }
    }
    
    public function show(int $id): void
    {
        $idea = $this->ideaModel->findByIdWithDetails($id);
        
        if (!$idea) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Idée non trouvée'];
            header('Location: /admin/ideas');
            exit;
        }
        
        // Get evaluations and feedback for this idea
        $evaluations = $this->ideaModel->getEvaluations($id);
        $feedback = $this->ideaModel->getFeedback($id);
        
        // Calculate average score
        $averageScore = 0;
        if (!empty($evaluations)) {
            $totalScore = array_sum(array_column($evaluations, 'score'));
            $averageScore = round($totalScore / count($evaluations), 2);
        }
        
        $data = [
            'title' => 'Détails de l\'Idée',
            'idea' => $idea,
            'evaluations' => $evaluations,
            'feedback' => $feedback,
            'average_score' => $averageScore,
            'user' => Auth::user()
        ];
        
        $this->view->render('admin/ideas/show', $data, 'admin');
    }
    
    public function updateStatus(int $id): void
    {
        // Validate CSRF token
        if (!Auth::validateCsrfToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Token CSRF invalide'];
            header("Location: /admin/ideas/{$id}");
            exit;
        }
        
        $idea = $this->ideaModel->findById($id);
        if (!$idea) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Idée non trouvée'];
            header('Location: /admin/ideas');
            exit;
        }
        
        $newStatus = $_POST['status'] ?? '';
        
        // Validate status
        if (!in_array($newStatus, ['submitted', 'under_review', 'accepted', 'rejected'])) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Statut invalide'];
            header("Location: /admin/ideas/{$id}");
            exit;
        }
        
        $updateData = ['status' => $newStatus];
        
        if ($this->ideaModel->updateStatus($id, $newStatus)) {
            $statusText = match($newStatus) {
                'accepted' => 'acceptée',
                'rejected' => 'rejetée',
                'under_review' => 'mise en révision',
                'submitted' => 'remise en attente'
            };
            $_SESSION['flash'] = ['type' => 'success', 'message' => "Idée {$statusText} avec succès"];
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Erreur lors de la mise à jour du statut. Veuillez vérifier les données.'];
        }
        
        // Redirect back to ideas list for bulk actions, otherwise to detail page
        $redirect = $_POST['redirect'] ?? 'detail';
        if ($redirect === 'list') {
            header('Location: /admin/ideas');
        } else {
            header("Location: /admin/ideas/{$id}");
        }
        exit;
    }
    
    public function addFeedback(int $id): void
    {
        // Validate CSRF token
        if (!Auth::validateCsrfToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Token CSRF invalide'];
            header("Location: /admin/ideas/{$id}");
            exit;
        }
        
        $idea = $this->ideaModel->findById($id);
        if (!$idea) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Idée non trouvée'];
            header('Location: /admin/ideas');
            exit;
        }
        
        $message = trim($_POST['message'] ?? '');
        
        if (empty($message)) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Le message de feedback est requis'];
            header("Location: /admin/ideas/{$id}");
            exit;
        }
        
        if (strlen($message) < 10) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Le message doit contenir au moins 10 caractères'];
            header("Location: /admin/ideas/{$id}");
            exit;
        }
        
        $feedbackData = [
            'idea_id' => $id,
            'admin_id' => Auth::user()['id'],
            'message' => $message
        ];
        
        if ($this->feedbackModel->create($feedbackData)) {
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Feedback administrateur ajouté avec succès'];
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Erreur lors de l\'ajout du feedback'];
        }
        
        header("Location: /admin/ideas/{$id}");
        exit;
    }
    
    public function bulkAction(): void
    {
        // Validate CSRF token
        if (!Auth::validateCsrfToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Token CSRF invalide'];
            header('Location: /admin/ideas');
            exit;
        }
        
        $action = $_POST['bulk_action'] ?? '';
        $selectedIds = $_POST['selected_ideas'] ?? [];
        
        if (empty($selectedIds) || !is_array($selectedIds)) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Aucune idée sélectionnée'];
            header('Location: /admin/ideas');
            exit;
        }
        
        if (!in_array($action, ['approve', 'reject', 'pending'])) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Action non valide'];
            header('Location: /admin/ideas');
            exit;
        }
        
        $status = match($action) {
            'approve' => 'approved',
            'reject' => 'rejected',
            'pending' => 'pending'
        };
        
        $successCount = 0;
        foreach ($selectedIds as $id) {
            if ($this->ideaModel->update((int)$id, ['status' => $status])) {
                $successCount++;
            }
        }
        
        if ($successCount > 0) {
            $actionText = match($action) {
                'approve' => 'approuvées',
                'reject' => 'rejetées',
                'pending' => 'remises en attente'
            };
            $_SESSION['flash'] = ['type' => 'success', 'message' => "{$successCount} idées {$actionText} avec succès"];
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Aucune idée n\'a pu être mise à jour'];
        }
        
        header('Location: /admin/ideas');
        exit;
    }
}
