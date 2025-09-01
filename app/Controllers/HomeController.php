<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Models\Idea;
use App\Models\Theme;
use App\Models\User;
use App\Models\Evaluation;

/**
 * Home Controller
 * Handles the main dashboard and home page
 */
class HomeController extends Controller
{
    private $ideaModel;
    private $themeModel;
    private $userModel;
    private $evaluationModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->ideaModel = new Idea();
        $this->themeModel = new Theme();
        $this->userModel = new User();
        $this->evaluationModel = new Evaluation();
    }
    
    /**
     * Display home page / dashboard
     */
    public function index()
    {
        if (!Auth::check()) {
            $this->redirect('/login');
            return;
        }
        
        $user = Auth::user();
        $data = [
            'user' => $user,
            'pageTitle' => 'Dashboard'
        ];
        
        // Get role-specific dashboard data
        switch ($user['role']) {
            case 'admin':
                $data = array_merge($data, $this->getAdminDashboardData());
                $this->render('admin/dashboard', $data, 'admin');
                break;
                
            case 'salarie':
                $data = array_merge($data, $this->getSalarieDashboardData($user['id']));
                $this->render('salarie/dashboard', $data, 'front');
                break;
                
            case 'evaluateur':
                $data = array_merge($data, $this->getEvaluateurDashboardData($user['id']));
                $this->render('evaluateur/dashboard', $data, 'front');
                break;
                
            default:
                $this->redirect('/login');
                break;
        }
    }
    
    /**
     * Get admin dashboard data
     */
    private function getAdminDashboardData()
    {
        $ideaStats = $this->ideaModel->getStatistics();
        $userStats = $this->userModel->getStatistics();
        $themeStats = $this->themeModel->getStatistics();
        $evaluationStats = $this->evaluationModel->getStatistics();
        
        // Recent ideas
        $recentIdeas = $this->ideaModel->getAll(1, 5);
        
        // Top rated ideas
        $topIdeas = $this->ideaModel->getTopRated(5);
        
        // Ideas by status
        $ideasByStatus = [];
        foreach ($ideaStats as $stat) {
            $ideasByStatus[$stat['status']] = $stat['count'];
        }
        
        // Users by role
        $usersByRole = [];
        foreach ($userStats as $stat) {
            if (!isset($usersByRole[$stat['role']])) {
                $usersByRole[$stat['role']] = 0;
            }
            if ($stat['status'] === 'active') {
                $usersByRole[$stat['role']] += $stat['count'];
            }
        }
        
        return [
            'stats' => [
                'total_ideas' => array_sum($ideasByStatus),
                'total_users' => array_sum($usersByRole),
                'total_themes' => count($themeStats),
                'total_evaluations' => $evaluationStats['total_evaluations'] ?? 0
            ],
            'ideasByStatus' => $ideasByStatus,
            'usersByRole' => $usersByRole,
            'recentIdeas' => $recentIdeas,
            'topIdeas' => $topIdeas,
            'themeStats' => $themeStats
        ];
    }
    
    /**
     * Get salarie dashboard data
     */
    private function getSalarieDashboardData($userId)
    {
        $myIdeas = $this->ideaModel->getByUser($userId, 1, 10);
        $themes = $this->themeModel->getActive();
        
        // Count ideas by status
        $ideaStatusCounts = [
            'submitted' => 0,
            'under_review' => 0,
            'accepted' => 0,
            'rejected' => 0,
            'archived' => 0
        ];
        
        foreach ($myIdeas as $idea) {
            $ideaStatusCounts[$idea['status']]++;
        }
        
        return [
            'myIdeas' => $myIdeas,
            'themes' => $themes,
            'ideaStatusCounts' => $ideaStatusCounts,
            'stats' => [
                'total_ideas' => count($myIdeas),
                'avg_score' => $this->calculateAverageScore($myIdeas)
            ]
        ];
    }
    
    /**
     * Get evaluateur dashboard data
     */
    private function getEvaluateurDashboardData($userId)
    {
        $myEvaluations = $this->evaluationModel->getByEvaluator($userId, 1, 10);
        $pendingIdeas = $this->evaluationModel->getPendingForEvaluator($userId);
        $evaluatorStats = $this->evaluationModel->getEvaluatorStats($userId);
        
        return [
            'myEvaluations' => $myEvaluations,
            'pendingIdeas' => array_slice($pendingIdeas, 0, 5), // Show first 5
            'pendingCount' => count($pendingIdeas),
            'stats' => $evaluatorStats
        ];
    }
    
    /**
     * Calculate average score for ideas
     */
    private function calculateAverageScore($ideas)
    {
        $scores = array_filter(array_column($ideas, 'avg_score'));
        
        if (empty($scores)) {
            return 0;
        }
        
        return round(array_sum($scores) / count($scores), 2);
    }
}
