<?php
declare(strict_types=1);

namespace App\Controllers\Salarie;

use App\Core\Auth;
use App\Core\View;
use App\Models\Idea;
use App\Models\Theme;

class IdeaController
{
    private View $view;
    private Idea $ideaModel;
    private Theme $themeModel;
    
    public function __construct()
    {
        $this->view = new View();
        $this->ideaModel = new Idea();
        $this->themeModel = new Theme();
        
        // Ensure user is authenticated and is salarie
        if (!Auth::isAuthenticated() || !Auth::hasRole('salarie')) {
            header('Location: /login');
            exit;
        }
    }
    
    public function index(): void
    {
        $userId = Auth::user()['id'];
        $ideas = $this->ideaModel->getByUserId($userId);
        
        $data = [
            'title' => 'Mes Idées',
            'ideas' => $ideas,
            'user' => Auth::user()
        ];
        
        $this->view->render('salarie/ideas/index', $data, 'front');
    }
    
    public function create(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->store();
            return;
        }
        
        $themes = $this->themeModel->getActive();
        
        $data = [
            'title' => 'Proposer une Idée',
            'themes' => $themes,
            'user' => Auth::user()
        ];
        
        $this->view->render('salarie/ideas/create', $data, 'front');
    }
    
    public function store(): void
    {
        // Validate CSRF token
        if (!Auth::validateCsrfToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Token CSRF invalide'];
            header('Location: /salarie/ideas/create');
            exit;
        }
        
        $data = [
            'title' => trim($_POST['title'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'expected_impact' => trim($_POST['expected_impact'] ?? ''),
            'required_resources' => trim($_POST['required_resources'] ?? ''),
            'theme_id' => (int)($_POST['theme_id'] ?? 0),
            'user_id' => Auth::user()['id'],
            'status' => 'submitted'
        ];
        
        // Validate required fields
        $errors = $this->validateIdeaData($data);
        
        if (!empty($errors)) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => implode('<br>', $errors)];
            $_SESSION['old_input'] = $data;
            header('Location: /salarie/ideas/create');
            exit;
        }
        
        // Validate theme exists and is active
        $theme = $this->themeModel->findById($data['theme_id']);
        if (!$theme || !$theme['is_active']) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Thématique non valide ou inactive'];
            $_SESSION['old_input'] = $data;
            header('Location: /salarie/ideas/create');
            exit;
        }
        
        if ($this->ideaModel->create($data)) {
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Idée proposée avec succès'];
            header('Location: /salarie/ideas');
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Erreur lors de la création de l\'idée'];
            header('Location: /salarie/ideas/create');
        }
        exit;
    }
    
    public function show(int $id): void
    {
        $idea = $this->ideaModel->findByIdWithDetails($id);
        
        if (!$idea || $idea['user_id'] !== Auth::user()['id']) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Idée non trouvée'];
            header('Location: /salarie/ideas');
            exit;
        }
        
        // Get evaluations and feedback for this idea
        $evaluations = $this->ideaModel->getEvaluations($id);
        $feedback = $this->ideaModel->getFeedback($id);
        
        // Calculate average score
        $averageScore = null;
        if (!empty($evaluations)) {
            $totalScore = 0;
            foreach ($evaluations as $evaluation) {
                $totalScore += $evaluation['score'];
            }
            $averageScore = $totalScore / count($evaluations);
        }
        
        $data = [
            'title' => 'Détails de l\'Idée',
            'idea' => $idea,
            'evaluations' => $evaluations,
            'feedback' => $feedback,
            'averageScore' => $averageScore,
            'user' => Auth::user()
        ];
        
        $this->view->render('salarie/ideas/show', $data, 'front');
    }
    
    public function edit(int $id): void
    {
        $idea = $this->ideaModel->findById($id);
        
        if (!$idea || $idea['user_id'] !== Auth::user()['id']) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Idée non trouvée'];
            header('Location: /salarie/ideas');
            exit;
        }
        
        // Only allow editing if idea is still submitted (not yet reviewed)
        if (!$this->ideaModel->canEdit($id)) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Vous ne pouvez modifier que les idées en attente de révision'];
            header('Location: /salarie/ideas');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->update($id);
            return;
        }
        
        $themes = $this->themeModel->getActive();
        
        $data = [
            'title' => 'Modifier l\'Idée',
            'idea' => $idea,
            'themes' => $themes,
            'user' => Auth::user()
        ];
        
        $this->view->render('salarie/ideas/edit', $data, 'front');
    }
    
    public function update(int $id): void
    {
        $idea = $this->ideaModel->findById($id);
        
        if (!$idea || $idea['user_id'] !== Auth::user()['id']) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Idée non trouvée'];
            header('Location: /salarie/ideas');
            exit;
        }
        
        // Only allow editing if idea is still submitted
        if ($idea['status'] !== 'submitted') {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Vous ne pouvez modifier que les idées soumises'];
            header('Location: /salarie/ideas');
            exit;
        }
        
        // Validate CSRF token
        if (!Auth::validateCsrfToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Token CSRF invalide'];
            header("Location: /salarie/ideas/edit/{$id}");
            exit;
        }
        
        $data = [
            'title' => trim($_POST['title'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'expected_impact' => trim($_POST['expected_impact'] ?? ''),
            'required_resources' => trim($_POST['required_resources'] ?? ''),
            'theme_id' => (int)($_POST['theme_id'] ?? 0)
        ];
        
        // Validate required fields
        $errors = $this->validateIdeaData($data, false);
        
        if (!empty($errors)) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => implode('<br>', $errors)];
            header("Location: /salarie/ideas/edit/{$id}");
            exit;
        }
        
        // Validate theme exists and is active
        $theme = $this->themeModel->findById($data['theme_id']);
        if (!$theme || !$theme['is_active']) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Thématique non valide ou inactive'];
            header("Location: /salarie/ideas/edit/{$id}");
            exit;
        }
        
        if ($this->ideaModel->update($id, $data)) {
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Idée modifiée avec succès'];
            header('Location: /salarie/ideas');
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Erreur lors de la modification de l\'idée'];
            header("Location: /salarie/ideas/edit/{$id}");
        }
        exit;
    }
    
    public function delete(int $id): void
    {
        // Validate CSRF token
        if (!Auth::validateCsrfToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Token CSRF invalide'];
            header('Location: /salarie/ideas');
            exit;
        }
        
        $idea = $this->ideaModel->findById($id);
        
        if (!$idea || $idea['user_id'] !== Auth::user()['id']) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Idée non trouvée'];
            header('Location: /salarie/ideas');
            exit;
        }
        
        // Only allow deletion if idea is still pending
        if ($idea['status'] !== 'pending') {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Vous ne pouvez supprimer que les idées en attente'];
            header('Location: /salarie/ideas');
            exit;
        }
        
        if ($this->ideaModel->delete($id)) {
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Idée supprimée avec succès'];
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Erreur lors de la suppression de l\'idée'];
        }
        
        header('Location: /salarie/ideas');
        exit;
    }
    
    private function validateIdeaData(array $data, bool $requireAll = true): array
    {
        $errors = [];
        
        if (empty($data['title'])) {
            $errors[] = 'Le titre est requis';
        } elseif (strlen($data['title']) < 5) {
            $errors[] = 'Le titre doit contenir au moins 5 caractères';
        } elseif (strlen($data['title']) > 200) {
            $errors[] = 'Le titre ne peut pas dépasser 200 caractères';
        }
        
        if (empty($data['description'])) {
            $errors[] = 'La description est requise';
        } elseif (strlen($data['description']) < 20) {
            $errors[] = 'La description doit contenir au moins 20 caractères';
        }
        
        if ($requireAll && $data['theme_id'] <= 0) {
            $errors[] = 'Veuillez sélectionner une thématique';
        }
        
        return $errors;
    }

    /**
     * Download idea as PDF
     */
    public function downloadPdf(int $id): void
    {
        try {
            $idea = $this->ideaModel->findByIdWithDetails($id);
            
            if (!$idea || $idea['user_id'] !== Auth::user()['id']) {
                $_SESSION['flash'] = ['type' => 'error', 'message' => 'Idée non trouvée'];
                header('Location: /salarie/ideas');
                exit;
            }

            // Get evaluations and feedback for this idea
            $evaluations = $this->ideaModel->getEvaluations($id);
            $feedback = $this->ideaModel->getFeedback($id);

            // Generate PDF
            $pdfService = new \App\Services\PdfService();
            $pdf = $pdfService->generateIdeaPdf($idea, $evaluations, $feedback);

            // Clean filename
            $filename = 'Idee_' . preg_replace('/[^a-zA-Z0-9_-]/', '_', $idea['title']) . '_' . date('Y-m-d') . '.pdf';

            // Clear any previous output
            if (ob_get_length()) {
                ob_clean();
            }

            // Set headers for PDF download
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Cache-Control: private, max-age=0, must-revalidate');
            header('Pragma: public');

            // Output PDF and suppress any error output
            echo $pdf->Output('', 'S');
            exit;
            
        } catch (Exception $e) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Erreur lors de la génération du PDF: ' . $e->getMessage()];
            header('Location: /salarie/ideas/' . $id);
            exit;
        }
    }
}
