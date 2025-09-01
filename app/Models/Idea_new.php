<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

/**
 * Idea Model
 * Handles idea-related database operations
 */
class Idea
{
    private Database $db;
    private string $table = 'ideas';
    
    public function __construct()
    {
        $this->db = Database::getInstance();
    }
    
    /**
     * Find idea by ID with relationships
     */
    public function findById($id)
    {
        $stmt = $this->db->prepare("
            SELECT 
                i.*,
                u.first_name,
                u.last_name,
                u.email,
                t.name as theme_name
            FROM {$this->table} i
            JOIN users u ON i.user_id = u.id
            JOIN themes t ON i.theme_id = t.id
            WHERE i.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    /**
     * Find idea by ID with detailed information including evaluations and feedback
     */
    public function findByIdWithDetails($id)
    {
        // Get the basic idea information
        $idea = $this->findById($id);
        
        if (!$idea) {
            return null;
        }
        
        // Get evaluation details if any
        $evalStmt = $this->db->prepare("
            SELECT 
                e.*,
                eval_user.first_name as evaluator_first_name,
                eval_user.last_name as evaluator_last_name,
                eval_user.email as evaluator_email
            FROM evaluations e
            LEFT JOIN users eval_user ON e.evaluator_id = eval_user.id
            WHERE e.idea_id = ?
            ORDER BY e.created_at DESC
        ");
        $evalStmt->execute([$id]);
        $idea['evaluations'] = $evalStmt->fetchAll();
        
        // Get feedback if any
        $feedbackStmt = $this->db->prepare("
            SELECT 
                f.*,
                u.first_name,
                u.last_name,
                u.email
            FROM feedback f
            LEFT JOIN users u ON f.admin_id = u.id
            WHERE f.idea_id = ?
            ORDER BY f.created_at DESC
        ");
        $feedbackStmt->execute([$id]);
        $idea['feedback'] = $feedbackStmt->fetchAll();
        
        return $idea;
    }
    
    /**
     * Get all ideas with pagination and filters
     */
    public function getAll($page = 1, $perPage = 20, $filters = [])
    {
        $offset = ($page - 1) * $perPage;
        
        $sql = "
            SELECT 
                i.*,
                u.first_name,
                u.last_name,
                t.name as theme_name,
                COUNT(e.id) as evaluations_count
            FROM {$this->table} i
            JOIN users u ON i.user_id = u.id
            JOIN themes t ON i.theme_id = t.id
            LEFT JOIN evaluations e ON i.id = e.idea_id
        ";
        
        $whereConditions = [];
        $params = [];
        
        // Apply filters
        if (!empty($filters['status'])) {
            $whereConditions[] = "i.status = ?";
            $params[] = $filters['status'];
        }
        
        if (!empty($filters['theme_id'])) {
            $whereConditions[] = "i.theme_id = ?";
            $params[] = $filters['theme_id'];
        }
        
        if (!empty($filters['user_id'])) {
            $whereConditions[] = "i.user_id = ?";
            $params[] = $filters['user_id'];
        }
        
        if (!empty($filters['search'])) {
            $whereConditions[] = "(i.title LIKE ? OR i.description LIKE ?)";
            $params[] = "%{$filters['search']}%";
            $params[] = "%{$filters['search']}%";
        }
        
        if (!empty($whereConditions)) {
            $sql .= " WHERE " . implode(' AND ', $whereConditions);
        }
        
        $sql .= "
            GROUP BY i.id
            ORDER BY i.created_at DESC
            LIMIT ? OFFSET ?
        ";
        
        $params[] = $perPage;
        $params[] = $offset;
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    /**
     * Get ideas by user ID with evaluation data
     */
    public function getByUserId($userId)
    {
        $stmt = $this->db->prepare("
            SELECT 
                i.*,
                t.name as theme_name,
                AVG(e.score) as avg_score,
                COUNT(e.id) as evaluations_count
            FROM {$this->table} i
            JOIN themes t ON i.theme_id = t.id
            LEFT JOIN evaluations e ON i.id = e.idea_id
            WHERE i.user_id = ?
            GROUP BY i.id
            ORDER BY i.created_at DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Count ideas with filters
     */
    public function count($filters = [])
    {
        $sql = "
            SELECT COUNT(DISTINCT i.id) as total
            FROM {$this->table} i
            JOIN users u ON i.user_id = u.id
            JOIN themes t ON i.theme_id = t.id
        ";
        
        $whereConditions = [];
        $params = [];
        
        // Apply filters
        if (!empty($filters['status'])) {
            $whereConditions[] = "i.status = ?";
            $params[] = $filters['status'];
        }
        
        if (!empty($filters['theme_id'])) {
            $whereConditions[] = "i.theme_id = ?";
            $params[] = $filters['theme_id'];
        }
        
        if (!empty($filters['user_id'])) {
            $whereConditions[] = "i.user_id = ?";
            $params[] = $filters['user_id'];
        }
        
        if (!empty($filters['search'])) {
            $whereConditions[] = "(i.title LIKE ? OR i.description LIKE ?)";
            $params[] = "%{$filters['search']}%";
            $params[] = "%{$filters['search']}%";
        }
        
        if (!empty($whereConditions)) {
            $sql .= " WHERE " . implode(' AND ', $whereConditions);
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        
        return (int)$result['total'];
    }
    
    /**
     * Create new idea
     */
    public function create($data)
    {
        $sql = "
            INSERT INTO {$this->table} 
            (title, description, theme_id, user_id, status, expected_impact, required_resources, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
        ";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['title'],
            $data['description'],
            $data['theme_id'],
            $data['user_id'],
            $data['status'] ?? 'submitted',
            $data['expected_impact'] ?? null,
            $data['required_resources'] ?? null
        ]);
    }
    
    /**
     * Update idea
     */
    public function update($id, $data)
    {
        $sql = "
            UPDATE {$this->table} 
            SET title = ?, description = ?, theme_id = ?, expected_impact = ?, required_resources = ?, updated_at = NOW()
            WHERE id = ?
        ";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['title'],
            $data['description'],
            $data['theme_id'],
            $data['expected_impact'] ?? null,
            $data['required_resources'] ?? null,
            $id
        ]);
    }
    
    /**
     * Update idea status
     */
    public function updateStatus($id, $status, $adminComment = null)
    {
        $sql = "UPDATE {$this->table} SET status = ?, updated_at = NOW() WHERE id = ?";
        $params = [$status, $id];
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }
    
    /**
     * Delete idea
     */
    public function delete($id)
    {
        // First delete related evaluations
        $stmt = $this->db->prepare("DELETE FROM evaluations WHERE idea_id = ?");
        $stmt->execute([$id]);
        
        // Then delete related feedback
        $stmt = $this->db->prepare("DELETE FROM feedback WHERE idea_id = ?");
        $stmt->execute([$id]);
        
        // Finally delete the idea
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    /**
     * Get ideas by status
     */
    public function getByStatus($status, $limit = null)
    {
        $sql = "
            SELECT 
                i.*,
                u.first_name,
                u.last_name,
                u.email,
                t.name as theme_name
            FROM {$this->table} i
            JOIN users u ON i.user_id = u.id
            JOIN themes t ON i.theme_id = t.id
            WHERE i.status = ?
            ORDER BY i.created_at DESC
        ";
        
        if ($limit) {
            $sql .= " LIMIT ?";
        }
        
        $stmt = $this->db->prepare($sql);
        
        if ($limit) {
            $stmt->execute([$status, $limit]);
        } else {
            $stmt->execute([$status]);
        }
        
        return $stmt->fetchAll();
    }
    
    /**
     * Get popular ideas (most evaluated)
     */
    public function getPopular($limit = 10)
    {
        $stmt = $this->db->prepare("
            SELECT 
                i.*,
                u.first_name,
                u.last_name,
                t.name as theme_name,
                COUNT(e.id) as evaluations_count,
                AVG(e.score) as avg_score
            FROM {$this->table} i
            JOIN users u ON i.user_id = u.id
            JOIN themes t ON i.theme_id = t.id
            LEFT JOIN evaluations e ON i.id = e.idea_id
            WHERE i.status IN ('accepted', 'under_review')
            GROUP BY i.id
            HAVING evaluations_count > 0
            ORDER BY evaluations_count DESC, avg_score DESC
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }
    
    /**
     * Get statistics
     */
    public function getStatistics()
    {
        $stats = [];
        
        // Total ideas
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM {$this->table}");
        $stmt->execute();
        $stats['total'] = $stmt->fetch()['total'];
        
        // Ideas by status
        $stmt = $this->db->prepare("
            SELECT status, COUNT(*) as count 
            FROM {$this->table} 
            GROUP BY status
        ");
        $stmt->execute();
        $statusCounts = $stmt->fetchAll();
        
        foreach ($statusCounts as $status) {
            $stats['by_status'][$status['status']] = $status['count'];
        }
        
        // Ideas this month
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count 
            FROM {$this->table} 
            WHERE MONTH(created_at) = MONTH(NOW()) AND YEAR(created_at) = YEAR(NOW())
        ");
        $stmt->execute();
        $stats['this_month'] = $stmt->fetch()['count'];
        
        return $stats;
    }
    
    /**
     * Get idea evaluations
     */
    public function getEvaluations($ideaId)
    {
        $stmt = $this->db->prepare("
            SELECT 
                e.*,
                u.first_name as evaluator_name,
                u.last_name as evaluator_lastname,
                u.email as evaluator_email
            FROM evaluations e
            JOIN users u ON e.evaluator_id = u.id
            WHERE e.idea_id = ?
            ORDER BY e.created_at DESC
        ");
        $stmt->execute([$ideaId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Get idea feedback
     */
    public function getFeedback($ideaId)
    {
        $stmt = $this->db->prepare("
            SELECT 
                f.*,
                u.first_name as author_name,
                u.last_name as author_lastname,
                u.email as author_email
            FROM feedback f
            JOIN users u ON f.admin_id = u.id
            WHERE f.idea_id = ?
            ORDER BY f.created_at DESC
        ");
        $stmt->execute([$ideaId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Check if user owns idea
     */
    public function isOwner($ideaId, $userId)
    {
        $stmt = $this->db->prepare("SELECT user_id FROM {$this->table} WHERE id = ?");
        $stmt->execute([$ideaId]);
        $idea = $stmt->fetch();
        
        return $idea && $idea['user_id'] == $userId;
    }
    
    /**
     * Check if idea can be edited
     */
    public function canEdit($ideaId)
    {
        $stmt = $this->db->prepare("SELECT status FROM {$this->table} WHERE id = ?");
        $stmt->execute([$ideaId]);
        $idea = $stmt->fetch();
        
        return $idea && $idea['status'] === 'submitted';
    }
}
