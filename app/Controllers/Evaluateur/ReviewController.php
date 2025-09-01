<?php
declare(strict_types=1);

namespace App\Controllers\Evaluateur;

use App\Core\Auth;
use App\Core\View;
use App\Models\Idea;
use App\Models\Evaluation;
use App\Models\Feedback;

class ReviewController
{
    private View $view;
    private Idea $ideaModel;
    private Evaluation $evaluationModel;
    private Feedback $feedbackModel;
    
    public function __construct()
    {
        $this->view = new View();
        $this->ideaModel = new Idea();
        $this->evaluationModel = new Evaluation();
        $this->feedbackModel = new Feedback();
        
        // Ensure user is authenticated and is evaluateur
        if (!Auth::isAuthenticated() || !Auth::hasRole('evaluateur')) {
            header('Location: /login');
            exit;
        }
    }
    
    /**
     * Display evaluateur dashboard
     */
    public function index(): void
    {
        $userId = Auth::user()['id'];
        
        // Get dashboard statistics
        $stats = [
            'pending_ideas' => $this->ideaModel->countPendingForEvaluation(),
            'my_evaluations' => $this->evaluationModel->countByUser($userId),
            'average_score' => $this->evaluationModel->getAverageScoreByUser($userId),
            'my_feedback' => $this->feedbackModel->countByUser($userId)
        ];
        
        // Get pending ideas for evaluation (limit 5 for dashboard)
        $pendingIdeas = $this->ideaModel->getApprovedIdeas(1, 5);
        
        // Get recent evaluations by this evaluateur
        $recentEvaluations = $this->evaluationModel->getRecentByUser($userId, 5);
        
        $data = [
            'title' => 'Tableau de bord - Évaluateur',
            'stats' => $stats,
            'pendingIdeas' => $pendingIdeas,
            'recentEvaluations' => $recentEvaluations,
            'user' => Auth::user()
        ];
        
        $this->view->render('evaluateur/dashboard', $data, 'front');
    }
    
    /**
     * Display ideas list for evaluation
     */
    public function review(): void
    {
        $page = $_GET['page'] ?? 1;
        $perPage = 12;
        
        // Get filters
        $filters = [
            'theme_id' => $_GET['theme_id'] ?? null,
            'status' => $_GET['status'] ?? null,
            'search' => $_GET['search'] ?? null
        ];
        
        // Get ideas for evaluation
        $ideas = $this->ideaModel->getApprovedIdeas($page, $perPage, $filters);
        $total = $this->ideaModel->countApprovedIdeas($filters);
        
        // Get themes for filter
        $themes = $this->ideaModel->getActiveThemes();
        
        // Pagination
        $pagination = [
            'current' => (int)$page,
            'total' => $total,
            'per_page' => $perPage,
            'pages' => ceil($total / $perPage)
        ];
        
        $data = [
            'title' => 'Idées à Évaluer',
            'ideas' => $ideas,
            'themes' => $themes,
            'pagination' => $pagination,
            'filters' => $filters,
            'user' => Auth::user()
        ];
        
        $this->view->render('evaluateur/review', $data, 'front');
    }
    
    public function show(int $id): void
    {
        $idea = $this->ideaModel->findByIdWithDetails($id);
        
        if (!$idea) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Idée non trouvée'];
            header('Location: /evaluateur/review');
            exit;
        }
        
        // Get existing evaluation by current evaluateur
        $userId = Auth::user()['id'];
        $currentEvaluation = $this->evaluationModel->findByIdeaAndUser($id, $userId);
        
        // Get all other evaluations for this idea (excluding current user)
        $otherEvaluations = $this->evaluationModel->getAllForIdea($id, $userId);
        
        $data = [
            'title' => 'Évaluation d\'idée',
            'idea' => $idea,
            'currentEvaluation' => $currentEvaluation,
            'otherEvaluations' => $otherEvaluations,
            'user' => Auth::user()
        ];
        
        $this->view->render('evaluateur/show', $data, 'front');
    }
    
    public function evaluate(): void
    {
        // Get idea ID from POST data
        $id = (int)($_POST['idea_id'] ?? 0);
        
        if (!$id) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'ID d\'idée manquant'];
            header('Location: /evaluateur/review');
            exit;
        }
        
        $idea = $this->ideaModel->findById($id);
        
        if (!$idea || $idea['status'] !== 'accepted') {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Idée non trouvée ou non approuvée'];
            header('Location: /evaluateur/review');
            exit;
        }
        
        // Validate CSRF token
        if (!Auth::validateCsrfToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Token CSRF invalide'];
            header("Location: /evaluateur/review/{$id}");
            exit;
        }
        
        $userId = Auth::user()['id'];
        
        // Check if evaluateur already evaluated this idea
        $existingEvaluation = $this->evaluationModel->findByIdeaAndUser($id, $userId);
        
        $data = [
            'idea_id' => $id,
            'evaluator_id' => $userId,
            'score' => (int)($_POST['score'] ?? 0),
            'comment' => trim($_POST['comment'] ?? '')
        ];
        
        // Validate evaluation data
        $errors = $this->validateEvaluationData($data);
        
        if (!empty($errors)) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => implode('<br>', $errors)];
            header("Location: /evaluateur/review/{$id}");
            exit;
        }
        
        if ($existingEvaluation) {
            // Update existing evaluation
            if ($this->evaluationModel->update($existingEvaluation['id'], $data)) {
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Évaluation mise à jour avec succès'];
            } else {
                $_SESSION['flash'] = ['type' => 'error', 'message' => 'Erreur lors de la mise à jour de l\'évaluation'];
            }
        } else {
            // Create new evaluation
            if ($this->evaluationModel->create($data)) {
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Évaluation enregistrée avec succès'];
            } else {
                $_SESSION['flash'] = ['type' => 'error', 'message' => 'Erreur lors de l\'enregistrement de l\'évaluation'];
            }
        }
        
        header("Location: /evaluateur/review/{$id}");
        exit;
    }
    
    public function feedback(int $id): void
    {
        $idea = $this->ideaModel->findById($id);
        
        if (!$idea || $idea['status'] !== 'accepted') {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Idée non trouvée ou non approuvée'];
            header('Location: /evaluateur/review');
            exit;
        }

        // Handle POST request (feedback submission)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validate CSRF token
            if (!Auth::validateCsrfToken($_POST['csrf_token'] ?? '')) {
                $_SESSION['flash'] = ['type' => 'error', 'message' => 'Token CSRF invalide'];
                header("Location: /evaluateur/review/{$id}");
                exit;
            }
            
            $userId = Auth::user()['id'];
            
            $data = [
                'idea_id' => $id,
                'admin_id' => $userId,
                'message' => trim($_POST['message'] ?? '')
            ];
            
            // Validate feedback data
            if (empty($data['message'])) {
                $_SESSION['flash'] = ['type' => 'error', 'message' => 'Le message de feedback est requis'];
                header("Location: /evaluateur/review/{$id}");
                exit;
            }
            
            if (strlen($data['message']) < 10) {
                $_SESSION['flash'] = ['type' => 'error', 'message' => 'Le message doit contenir au moins 10 caractères'];
                header("Location: /evaluateur/review/{$id}");
                exit;
            }
            
            if ($this->feedbackModel->create($data)) {
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Feedback ajouté avec succès'];
            } else {
                $_SESSION['flash'] = ['type' => 'error', 'message' => 'Erreur lors de l\'ajout du feedback'];
            }
            
            header("Location: /evaluateur/review/{$id}");
            exit;
        }

        // Handle GET request (show feedback form)
        $data = [
            'title' => 'Ajouter un feedback',
            'idea' => $idea,
            'user' => Auth::user()
        ];
        
        $this->view->render('evaluateur/feedback', $data, 'front');
    }
    
    public function myEvaluations(): void
    {
        $userId = Auth::user()['id'];
        $evaluations = $this->evaluationModel->getByEvaluator($userId);
        
        // Get evaluator statistics
        $statsData = $this->evaluationModel->getEvaluatorStats($userId);
        
        // Calculate additional statistics
        $stats = [
            'total' => $statsData['total_evaluations'] ?? 0,
            'average_score' => $statsData['avg_score_given'] ?? 0,
            'this_month' => $this->evaluationModel->getThisMonthCount($userId),
            'excellent_given' => $this->evaluationModel->getExcellentCount($userId)
        ];
        
        $data = [
            'title' => 'Mes Évaluations',
            'evaluations' => $evaluations,
            'stats' => $stats,
            'user' => Auth::user()
        ];
        
        $this->view->render('evaluateur/my-evaluations', $data, 'front');
    }
    
    public function statistics(): void
    {
        $userId = Auth::user()['id'];
        
        $stats = [
            'total_evaluations' => $this->evaluationModel->countByUser($userId),
            'average_score' => $this->evaluationModel->getAverageScoreByUser($userId),
            'ideas_with_feedback' => $this->feedbackModel->countByUser($userId),
            'recent_evaluations' => $this->evaluationModel->getRecentByUser($userId, 10)
        ];
        
        $data = [
            'title' => 'Mes Statistiques',
            'stats' => $stats,
            'user' => Auth::user()
        ];
        
        $this->view->render('evaluateur/statistics', $data, 'front');
    }
    
    private function validateEvaluationData(array $data): array
    {
        $errors = [];
        
        if ($data['score'] < 1 || $data['score'] > 5) {
            $errors[] = 'La note doit être comprise entre 1 et 5';
        }
        
        if (empty($data['comment'])) {
            $errors[] = 'Le commentaire est requis';
        } elseif (strlen($data['comment']) < 10) {
            $errors[] = 'Le commentaire doit contenir au moins 10 caractères';
        } elseif (strlen($data['comment']) > 1000) {
            $errors[] = 'Le commentaire ne peut pas dépasser 1000 caractères';
        }
        
        return $errors;
    }
    
    /**
     * Display best ideas tracking page
     */
    public function bestIdeas(): void
    {
        $page = $_GET['page'] ?? 1;
        $perPage = 12;
        
        // Get filters
        $filters = [
            'period' => $_GET['period'] ?? 'all',
            'theme_id' => $_GET['theme_id'] ?? null,
            'min_score' => $_GET['min_score'] ?? 3,
            'sort' => $_GET['sort'] ?? 'score',
            'search' => $_GET['search'] ?? null
        ];
        
        // Get best ideas based on filters
        $bestIdeas = $this->ideaModel->getBestIdeas($page, $perPage, $filters);
        
        // Get themes for filter
        $themes = $this->ideaModel->getActiveThemes();
        
        $data = [
            'title' => 'Meilleures Idées',
            'bestIdeas' => $bestIdeas,
            'themes' => $themes,
            'filters' => $filters,
            'user' => Auth::user()
        ];
        
        $this->view->render('evaluateur/best-ideas', $data, 'front');
    }
}
