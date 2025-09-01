<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Auth;
use App\Models\User;
use App\Models\Theme;
use App\Models\Idea;

class ReportController extends Controller
{
    private User $userModel;
    private Theme $themeModel;
    private Idea $ideaModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new User();
        $this->themeModel = new Theme();
        $this->ideaModel = new Idea();
        
        // Ensure user is authenticated and is admin
        if (!Auth::check() || Auth::user()['role'] !== 'Admin') {
            $this->redirect('/login');
        }
    }

    public function index(): void
    {
        $data = [
            'pageTitle' => 'Rapports et Statistiques'
        ];

        $this->view('admin/reports/index', $data, 'admin');
    }

    public function users(): void
    {
        // User statistics
        $userStats = [
            'total' => $this->userModel->count(),
            'by_role' => $this->userModel->countByRole(),
            'recent_registrations' => $this->userModel->getRecentRegistrations(30),
            'active_users' => $this->userModel->getActiveUsers(30)
        ];

        $data = [
            'pageTitle' => 'Rapport des Utilisateurs',
            'stats' => $userStats
        ];

        $this->view('admin/reports/users', $data, 'admin');
    }

    public function ideas(): void
    {
        // Idea statistics
        $ideaStats = [
            'total' => $this->ideaModel->count(),
            'by_status' => $this->ideaModel->countByStatus(),
            'by_theme' => $this->ideaModel->countByTheme(),
            'monthly_submissions' => $this->ideaModel->getMonthlySubmissions(12),
            'top_contributors' => $this->ideaModel->getTopContributors(10)
        ];

        $data = [
            'pageTitle' => 'Rapport des Idées',
            'stats' => $ideaStats
        ];

        $this->view('admin/reports/ideas', $data, 'admin');
    }

    public function themes(): void
    {
        // Theme statistics
        $themeStats = [
            'total' => $this->themeModel->count(),
            'active' => $this->themeModel->countActive(),
            'ideas_per_theme' => $this->themeModel->getIdeasCount(),
            'popular_themes' => $this->themeModel->getMostPopular(10)
        ];

        $data = [
            'pageTitle' => 'Rapport des Thématiques',
            'stats' => $themeStats
        ];

        $this->view('admin/reports/themes', $data, 'admin');
    }

    public function export(): void
    {
        $type = $_GET['type'] ?? 'users';
        $format = $_GET['format'] ?? 'csv';

        switch ($type) {
            case 'users':
                $this->exportUsers($format);
                break;
            case 'ideas':
                $this->exportIdeas($format);
                break;
            case 'themes':
                $this->exportThemes($format);
                break;
            default:
                $this->flashMessage('error', 'Type d\'export invalide.');
                $this->redirect('/admin/reports');
        }
    }

    private function exportUsers(string $format): void
    {
        $users = $this->userModel->getAll();
        
        if ($format === 'csv') {
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="users_export_' . date('Y-m-d') . '.csv"');
            
            $output = fopen('php://output', 'w');
            fputcsv($output, ['ID', 'Prénom', 'Nom', 'Email', 'Rôle', 'Date de création']);
            
            foreach ($users as $user) {
                fputcsv($output, [
                    $user['id'],
                    $user['first_name'],
                    $user['last_name'],
                    $user['email'],
                    $user['role'],
                    $user['created_at']
                ]);
            }
            
            fclose($output);
        }
    }

    private function exportIdeas(string $format): void
    {
        $ideas = $this->ideaModel->getAllWithDetails();
        
        if ($format === 'csv') {
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="ideas_export_' . date('Y-m-d') . '.csv"');
            
            $output = fopen('php://output', 'w');
            fputcsv($output, ['ID', 'Titre', 'Auteur', 'Thématique', 'Statut', 'Date de soumission']);
            
            foreach ($ideas as $idea) {
                fputcsv($output, [
                    $idea['id'],
                    $idea['title'],
                    $idea['author_name'],
                    $idea['theme_name'],
                    $idea['status'],
                    $idea['created_at']
                ]);
            }
            
            fclose($output);
        }
    }

    private function exportThemes(string $format): void
    {
        $themes = $this->themeModel->getAll();
        
        if ($format === 'csv') {
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="themes_export_' . date('Y-m-d') . '.csv"');
            
            $output = fopen('php://output', 'w');
            fputcsv($output, ['ID', 'Nom', 'Description', 'Statut', 'Date de création']);
            
            foreach ($themes as $theme) {
                fputcsv($output, [
                    $theme['id'],
                    $theme['name'],
                    $theme['description'],
                    $theme['is_active'] ? 'Actif' : 'Inactif',
                    $theme['created_at']
                ]);
            }
            
            fclose($output);
        }
    }
}
