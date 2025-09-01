<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\View;
use App\Models\User;
use App\Models\Theme;
use App\Models\Idea;

class DashboardController
{
    private View $view;
    
    public function __construct()
    {
        $this->view = new View();
        
        // Ensure user is authenticated and is admin
        if (!Auth::isAuthenticated() || !Auth::hasRole('admin')) {
            header('Location: /login');
            exit;
        }
    }
    
    public function index(): void
    {
        $userModel = new User();
        $themeModel = new Theme();
        $ideaModel = new Idea();
        
        // Get dashboard statistics
        $stats = [
            'total_users' => $userModel->count(),
            'total_themes' => $themeModel->count(),
            'total_ideas' => $ideaModel->count(),
            'pending_ideas' => $ideaModel->countByStatus('submitted'),
            'under_review_ideas' => $ideaModel->countByStatus('under_review'),
            'approved_ideas' => $ideaModel->countByStatus('accepted'),
            'rejected_ideas' => $ideaModel->countByStatus('rejected'),
        ];
        
        // Get recent activities
        $recentIdeas = $ideaModel->getRecent(5);
        $recentUsers = $userModel->getRecent(5);
        
        $data = [
            'title' => 'Tableau de Bord',
            'stats' => $stats,
            'recent_ideas' => $recentIdeas,
            'recent_users' => $recentUsers,
            'user' => Auth::user()
        ];
        
        $this->view->render('admin/dashboard', $data, 'admin');
    }
}
