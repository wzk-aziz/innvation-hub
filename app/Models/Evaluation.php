<?php

namespace App\Models;

use App\Core\Database;

/**
 * Evaluation Model
 * Handles evaluation-related database operations
 */
class Evaluation
{
    private $db;
    private $table = 'evaluations';
    
    public function __construct()
    {
        $this->db = Database::getInstance();
    }
    
    /**
     * Find evaluation by ID
     */
    public function findById($id)
    {
        $stmt = $this->db->prepare("
            SELECT 
                e.*,
                i.title as idea_title,
                u.first_name,
                u.last_name
            FROM {$this->table} e
            JOIN ideas i ON e.idea_id = i.id
            JOIN users u ON e.evaluator_id = u.id
            WHERE e.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    /**
     * Find evaluation by idea and evaluator
     */
    public function findByIdeaAndEvaluator($ideaId, $evaluatorId)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE idea_id = ? AND evaluator_id = ?");
        $stmt->execute([$ideaId, $evaluatorId]);
        return $stmt->fetch();
    }
    
    /**
     * Get evaluations for an idea
     */
    public function getByIdea($ideaId)
    {
        $stmt = $this->db->prepare("
            SELECT 
                e.*,
                u.first_name,
                u.last_name
            FROM {$this->table} e
            JOIN users u ON e.evaluator_id = u.id
            WHERE e.idea_id = ?
            ORDER BY e.created_at DESC
        ");
        $stmt->execute([$ideaId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Get evaluations by evaluator
     */
    public function getByEvaluator($evaluatorId, $page = 1, $perPage = 20)
    {
        $offset = ($page - 1) * $perPage;
        
        $stmt = $this->db->prepare("
            SELECT 
                e.*,
                i.title as idea_title,
                i.description as idea_description,
                i.status as idea_status,
                u.first_name as author_first_name,
                u.last_name as author_last_name,
                t.name as theme_name,
                (SELECT AVG(e2.score) FROM evaluations e2 WHERE e2.idea_id = i.id) as avg_score,
                (SELECT COUNT(*) FROM evaluations e2 WHERE e2.idea_id = i.id) as total_evaluations
            FROM {$this->table} e
            JOIN ideas i ON e.idea_id = i.id
            JOIN users u ON i.user_id = u.id
            JOIN themes t ON i.theme_id = t.id
            WHERE e.evaluator_id = ?
            ORDER BY e.created_at DESC
            LIMIT ? OFFSET ?
        ");
        
        $stmt->execute([$evaluatorId, $perPage, $offset]);
        return $stmt->fetchAll();
    }
    
    /**
     * Create or update evaluation
     */
    public function createOrUpdate($data)
    {
        // Check if evaluation already exists
        $existing = $this->findByIdeaAndEvaluator($data['idea_id'], $data['evaluator_id']);
        
        if ($existing) {
            // Update existing evaluation
            return $this->update($existing['id'], $data);
        } else {
            // Create new evaluation
            return $this->create($data);
        }
    }
    
    /**
     * Create new evaluation
     */
    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} (idea_id, evaluator_id, score, comment) 
                VALUES (?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            $data['idea_id'],
            $data['evaluator_id'],
            $data['score'],
            $data['comment'] ?? null
        ]);
        
        if ($result) {
            $evaluationId = $this->db->lastInsertId();
            
            // Update idea average score
            $ideaModel = new \App\Models\Idea();
            $ideaModel->updateAverageScore($data['idea_id']);
            
            return $evaluationId;
        }
        
        return false;
    }
    
    /**
     * Update evaluation
     */
    public function update($id, $data)
    {
        $fields = [];
        $params = [];
        
        $allowedFields = ['score', 'comment'];
        
        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $fields[] = "{$field} = ?";
                $params[] = $data[$field];
            }
        }
        
        if (empty($fields)) {
            return false;
        }
        
        $params[] = $id;
        
        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = ?";
        
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute($params);
        
        if ($result) {
            // Get idea_id and update average score
            $evaluation = $this->findById($id);
            if ($evaluation) {
                $ideaModel = new Idea();
                $ideaModel->updateAverageScore($evaluation['idea_id']);
            }
        }
        
        return $result;
    }
    
    /**
     * Delete evaluation
     */
    public function delete($id)
    {
        // Get evaluation details before deletion
        $evaluation = $this->findById($id);
        
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        $result = $stmt->execute([$id]);
        
        if ($result && $evaluation) {
            // Update idea average score
            $ideaModel = new Idea();
            $ideaModel->updateAverageScore($evaluation['idea_id']);
        }
        
        return $result;
    }
    
    /**
     * Get evaluation statistics
     */
    public function getStatistics()
    {
        $stmt = $this->db->prepare("
            SELECT 
                AVG(score) as avg_score,
                MIN(score) as min_score,
                MAX(score) as max_score,
                COUNT(*) as total_evaluations,
                COUNT(DISTINCT idea_id) as evaluated_ideas,
                COUNT(DISTINCT evaluator_id) as active_evaluators
            FROM {$this->table}
        ");
        $stmt->execute();
        return $stmt->fetch();
    }
    
    /**
     * Get score distribution
     */
    public function getScoreDistribution()
    {
        $stmt = $this->db->prepare("
            SELECT 
                score,
                COUNT(*) as count
            FROM {$this->table}
            GROUP BY score
            ORDER BY score
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Get evaluator statistics
     */
    public function getEvaluatorStats($evaluatorId)
    {
        $stmt = $this->db->prepare("
            SELECT 
                COUNT(*) as total_evaluations,
                AVG(score) as avg_score_given,
                MIN(score) as min_score_given,
                MAX(score) as max_score_given,
                COUNT(DISTINCT idea_id) as ideas_evaluated
            FROM {$this->table}
            WHERE evaluator_id = ?
        ");
        $stmt->execute([$evaluatorId]);
        return $stmt->fetch();
    }
    
    /**
     * Check if evaluator has evaluated idea
     */
    public function hasEvaluated($ideaId, $evaluatorId)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM {$this->table} WHERE idea_id = ? AND evaluator_id = ?");
        $stmt->execute([$ideaId, $evaluatorId]);
        $result = $stmt->fetch();
        
        return $result['count'] > 0;
    }
    
    /**
     * Get ideas pending evaluation by evaluator
     */
    public function getPendingForEvaluator($evaluatorId)
    {
        $stmt = $this->db->prepare("
            SELECT 
                i.id,
                i.title,
                i.status,
                t.name as theme_name,
                u.first_name,
                u.last_name
            FROM ideas i
            JOIN themes t ON i.theme_id = t.id
            JOIN users u ON i.user_id = u.id
            LEFT JOIN {$this->table} e ON i.id = e.idea_id AND e.evaluator_id = ?
            WHERE i.user_id != ? 
            AND i.status IN ('submitted', 'under_review')
            AND e.id IS NULL
            ORDER BY i.created_at ASC
        ");
        $stmt->execute([$evaluatorId, $evaluatorId]);
        return $stmt->fetchAll();
    }

    /**
     * Count evaluations by user
     */
    public function countByUser($userId)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM {$this->table} WHERE evaluator_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch()['count'];
    }

    /**
     * Get average score by user
     */
    public function getAverageScoreByUser($userId)
    {
        $stmt = $this->db->prepare("SELECT AVG(score) as average FROM {$this->table} WHERE evaluator_id = ?");
        $stmt->execute([$userId]);
        $result = $stmt->fetch();
        return $result['average'] ? round($result['average'], 2) : 0;
    }

    /**
     * Get recent evaluations by user
     */
    public function getRecentByUser($userId, $limit = 10)
    {
        $stmt = $this->db->prepare("
            SELECT 
                e.*,
                i.title as idea_title,
                i.theme_id,
                t.name as theme_name,
                u.first_name as author_first_name,
                u.last_name as author_last_name
            FROM {$this->table} e
            JOIN ideas i ON e.idea_id = i.id
            JOIN themes t ON i.theme_id = t.id
            JOIN users u ON i.user_id = u.id
            WHERE e.evaluator_id = ?
            ORDER BY e.created_at DESC
            LIMIT ?
        ");
        $stmt->execute([$userId, $limit]);
        return $stmt->fetchAll();
    }
    
    /**
     * Find evaluation by idea and user (alias for compatibility)
     */
    public function findByIdeaAndUser($ideaId, $userId)
    {
        return $this->findByIdeaAndEvaluator($ideaId, $userId);
    }
    
    /**
     * Get all evaluations for an idea (excluding specific user)
     */
    public function getAllForIdea($ideaId, $excludeUserId = null)
    {
        $sql = "
            SELECT 
                e.*,
                u.first_name as evaluator_first_name,
                u.last_name as evaluator_last_name
            FROM {$this->table} e
            JOIN users u ON e.evaluator_id = u.id
            WHERE e.idea_id = ?
        ";
        
        $params = [$ideaId];
        
        if ($excludeUserId) {
            $sql .= " AND e.evaluator_id != ?";
            $params[] = $excludeUserId;
        }
        
        $sql .= " ORDER BY e.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    /**
     * Get count of evaluations made this month by user
     */
    public function getThisMonthCount($userId)
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count 
            FROM {$this->table} 
            WHERE evaluator_id = ? 
            AND MONTH(created_at) = MONTH(NOW()) 
            AND YEAR(created_at) = YEAR(NOW())
        ");
        $stmt->execute([$userId]);
        $result = $stmt->fetch();
        return $result['count'] ?? 0;
    }
    
    /**
     * Get count of excellent evaluations (score 5) given by user
     */
    public function getExcellentCount($userId)
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count 
            FROM {$this->table} 
            WHERE evaluator_id = ? 
            AND score = 5
        ");
        $stmt->execute([$userId]);
        $result = $stmt->fetch();
        return $result['count'] ?? 0;
    }
}
