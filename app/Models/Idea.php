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
        // Build dynamic SQL based on provided fields
        $fields = [];
        $values = [];
        
        if (isset($data['title'])) {
            $fields[] = 'title = ?';
            $values[] = $data['title'];
        }
        
        if (isset($data['description'])) {
            $fields[] = 'description = ?';
            $values[] = $data['description'];
        }
        
        if (isset($data['theme_id'])) {
            $fields[] = 'theme_id = ?';
            $values[] = $data['theme_id'];
        }
        
        if (isset($data['expected_impact'])) {
            $fields[] = 'expected_impact = ?';
            $values[] = $data['expected_impact'];
        }
        
        if (isset($data['required_resources'])) {
            $fields[] = 'required_resources = ?';
            $values[] = $data['required_resources'];
        }
        
        if (isset($data['status'])) {
            $fields[] = 'status = ?';
            $values[] = $data['status'];
        }
        
        if (empty($fields)) {
            return false; // No fields to update
        }
        
        // Always update the timestamp
        $fields[] = 'updated_at = NOW()';
        $values[] = $id; // Add ID for WHERE clause
        
        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = ?";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($values);
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
                u.first_name as evaluator_first_name,
                u.last_name as evaluator_last_name,
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
                u.first_name,
                u.last_name,
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

    /**
     * Count ideas by status
     */
    public function countByStatus($status)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM {$this->table} WHERE status = ?");
        $stmt->execute([$status]);
        return $stmt->fetch()['total'];
    }

    /**
     * Get recent ideas
     */
    public function getRecent($limit = 10)
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
            ORDER BY i.created_at DESC
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }

    /**
     * Get ideas that are ready for evaluation (submitted or under review)
     */
    public function getApprovedIdeas($page = 1, $perPage = 20, $filters = [])
    {
        $offset = ($page - 1) * $perPage;
        $whereConditions = ["i.status = 'accepted'"];
        $params = [];
        
        // Apply filters
        if (!empty($filters['theme_id'])) {
            $whereConditions[] = "i.theme_id = ?";
            $params[] = $filters['theme_id'];
        }
        
        if (!empty($filters['status'])) {
            $whereConditions[] = "i.status = ?";
            $params[] = $filters['status'];
        }
        
        if (!empty($filters['search'])) {
            $whereConditions[] = "(i.title LIKE ? OR i.description LIKE ? OR u.first_name LIKE ? OR u.last_name LIKE ?)";
            $searchTerm = "%{$filters['search']}%";
            $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
        }
        
        $whereClause = implode(' AND ', $whereConditions);
        
        $stmt = $this->db->prepare("
            SELECT 
                i.*,
                u.first_name as author_first_name,
                u.last_name as author_last_name,
                u.email as author_email,
                t.name as theme_name,
                COUNT(e.id) as evaluations_count,
                AVG(e.score) as average_score
            FROM {$this->table} i
            JOIN users u ON i.user_id = u.id
            JOIN themes t ON i.theme_id = t.id
            LEFT JOIN evaluations e ON i.id = e.idea_id
            WHERE {$whereClause}
            GROUP BY i.id
            ORDER BY i.created_at DESC
            LIMIT ? OFFSET ?
        ");
        
        $params[] = $perPage;
        $params[] = $offset;
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Count pending ideas for evaluation
     */
    public function countPendingForEvaluation()
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count 
            FROM {$this->table} 
            WHERE status = 'accepted'
        ");
        $stmt->execute();
        return $stmt->fetch()['count'];
    }

    /**
     * Count approved ideas with filters
     */
    public function countApprovedIdeas($filters = [])
    {
        $whereConditions = ["status = 'accepted'"];
        $params = [];
        
        // Apply same filters as getApprovedIdeas
        if (!empty($filters['theme_id'])) {
            $whereConditions[] = "theme_id = ?";
            $params[] = $filters['theme_id'];
        }
        
        if (!empty($filters['status'])) {
            $whereConditions[] = "status = ?";
            $params[] = $filters['status'];
        }
        
        if (!empty($filters['search'])) {
            $whereConditions[] = "(title LIKE ? OR description LIKE ?)";
            $searchTerm = "%{$filters['search']}%";
            $params = array_merge($params, [$searchTerm, $searchTerm]);
        }
        
        $whereClause = implode(' AND ', $whereConditions);
        
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM {$this->table} WHERE {$whereClause}");
        $stmt->execute($params);
        return $stmt->fetch()['count'];
    }

    /**
     * Get active themes
     */
    public function getActiveThemes()
    {
        $stmt = $this->db->prepare("SELECT * FROM themes WHERE is_active = 1 ORDER BY name");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Get best ideas (highest scoring)
     */
    public function getBestIdeas($page = 1, $perPage = 20, $filters = [])
    {
        $offset = ($page - 1) * $perPage;
        $whereConditions = ["i.status != 'rejected'"];
        $params = [];
        
        // Apply filters
        if (!empty($filters['theme_id'])) {
            $whereConditions[] = "i.theme_id = ?";
            $params[] = $filters['theme_id'];
        }

        $havingConditions = ["evaluation_count > 0"];
        
        if (!empty($filters['min_score'])) {
            $havingConditions[] = "AVG(e.score) >= ?";
            $params[] = $filters['min_score'];
        }

        if (!empty($filters['search'])) {
            $whereConditions[] = "(i.title LIKE ? OR i.description LIKE ? OR u.first_name LIKE ? OR u.last_name LIKE ?)";
            $searchTerm = "%{$filters['search']}%";
            $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
        }
        
        // Period filter
        if (!empty($filters['period']) && $filters['period'] !== 'all') {
            switch ($filters['period']) {
                case 'week':
                    $whereConditions[] = "i.created_at >= DATE_SUB(NOW(), INTERVAL 1 WEEK)";
                    break;
                case 'month':
                    $whereConditions[] = "i.created_at >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
                    break;
                case 'quarter':
                    $whereConditions[] = "i.created_at >= DATE_SUB(NOW(), INTERVAL 3 MONTH)";
                    break;
                case 'year':
                    $whereConditions[] = "i.created_at >= DATE_SUB(NOW(), INTERVAL 1 YEAR)";
                    break;
            }
        }
        
        $whereClause = implode(' AND ', $whereConditions);
        $havingClause = implode(' AND ', $havingConditions);
        
        // Sort options
        $orderBy = "ORDER BY avg_score DESC, evaluation_count DESC";
        if (!empty($filters['sort'])) {
            switch ($filters['sort']) {
                case 'evaluations':
                    $orderBy = "ORDER BY evaluation_count DESC, avg_score DESC";
                    break;
                case 'recent':
                    $orderBy = "ORDER BY i.created_at DESC";
                    break;
                case 'evolution':
                    $orderBy = "ORDER BY avg_score DESC"; // Could be enhanced with weekly comparison
                    break;
            }
        }
        
        $stmt = $this->db->prepare("
            SELECT 
                i.*,
                u.first_name as author_first_name,
                u.last_name as author_last_name,
                u.email as author_email,
                t.name as theme_name,
                COUNT(e.id) as evaluation_count,
                AVG(e.score) as avg_score
            FROM {$this->table} i
            JOIN users u ON i.user_id = u.id
            JOIN themes t ON i.theme_id = t.id
            LEFT JOIN evaluations e ON i.id = e.idea_id
            WHERE {$whereClause}
            GROUP BY i.id
            HAVING {$havingClause}
            {$orderBy}
            LIMIT ? OFFSET ?
        ");
        
        $params[] = $perPage;
        $params[] = $offset;
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    /**
     * Update average score for an idea based on all evaluations
     */
    public function updateAverageScore($ideaId)
    {
        $stmt = $this->db->prepare("
            UPDATE {$this->table} 
            SET avg_score = (
                SELECT AVG(score) 
                FROM evaluations 
                WHERE idea_id = ?
            ) 
            WHERE id = ?
        ");
        
        return $stmt->execute([$ideaId, $ideaId]);
    }
}
