<?php

namespace App\Models;

use App\Core\Database;

/**
 * Feedback Model
 * Handles feedback-related database operations
 */
class Feedback
{
    private $db;
    private $table = 'feedback';
    
    public function __construct()
    {
        $this->db = Database::getInstance();
    }
    
    /**
     * Find feedback by ID
     */
    public function findById($id)
    {
        $stmt = $this->db->prepare("
            SELECT 
                f.*,
                i.title as idea_title,
                u.first_name as admin_first_name,
                u.last_name as admin_last_name
            FROM {$this->table} f
            JOIN ideas i ON f.idea_id = i.id
            JOIN users u ON f.admin_id = u.id
            WHERE f.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    /**
     * Get feedback for an idea
     */
    public function getByIdea($ideaId)
    {
        $stmt = $this->db->prepare("
            SELECT 
                f.*,
                u.first_name as admin_first_name,
                u.last_name as admin_last_name
            FROM {$this->table} f
            JOIN users u ON f.admin_id = u.id
            WHERE f.idea_id = ?
            ORDER BY f.created_at DESC
        ");
        $stmt->execute([$ideaId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Get feedback by admin
     */
    public function getByAdmin($adminId, $page = 1, $perPage = 20)
    {
        $offset = ($page - 1) * $perPage;
        
        $stmt = $this->db->prepare("
            SELECT 
                f.*,
                i.title as idea_title,
                i.status as idea_status,
                u.first_name as author_first_name,
                u.last_name as author_last_name,
                t.name as theme_name
            FROM {$this->table} f
            JOIN ideas i ON f.idea_id = i.id
            JOIN users u ON i.user_id = u.id
            JOIN themes t ON i.theme_id = t.id
            WHERE f.admin_id = ?
            ORDER BY f.created_at DESC
            LIMIT ? OFFSET ?
        ");
        
        $stmt->execute([$adminId, $perPage, $offset]);
        return $stmt->fetchAll();
    }
    
    /**
     * Create new feedback
     */
    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} (idea_id, admin_id, message) VALUES (?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            $data['idea_id'],
            $data['admin_id'],
            $data['message']
        ]);
        
        if ($result) {
            return $this->db->lastInsertId();
        }
        
        return false;
    }
    
    /**
     * Update feedback
     */
    public function update($id, $data)
    {
        $sql = "UPDATE {$this->table} SET message = ? WHERE id = ?";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['message'],
            $id
        ]);
    }
    
    /**
     * Delete feedback
     */
    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    /**
     * Get recent feedback
     */
    public function getRecent($limit = 10)
    {
        $stmt = $this->db->prepare("
            SELECT 
                f.*,
                i.title as idea_title,
                i.status as idea_status,
                u_admin.first_name as admin_first_name,
                u_admin.last_name as admin_last_name,
                u_author.first_name as author_first_name,
                u_author.last_name as author_last_name
            FROM {$this->table} f
            JOIN ideas i ON f.idea_id = i.id
            JOIN users u_admin ON f.admin_id = u_admin.id
            JOIN users u_author ON i.user_id = u_author.id
            ORDER BY f.created_at DESC
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }
    
    /**
     * Get feedback statistics
     */
    public function getStatistics()
    {
        $stmt = $this->db->prepare("
            SELECT 
                COUNT(*) as total_feedback,
                COUNT(DISTINCT idea_id) as ideas_with_feedback,
                COUNT(DISTINCT admin_id) as active_admins,
                AVG(LENGTH(message)) as avg_message_length
            FROM {$this->table}
        ");
        $stmt->execute();
        return $stmt->fetch();
    }
    
    /**
     * Get feedback count by admin
     */
    public function getCountByAdmin()
    {
        $stmt = $this->db->prepare("
            SELECT 
                u.first_name,
                u.last_name,
                COUNT(f.id) as feedback_count
            FROM users u
            LEFT JOIN {$this->table} f ON u.id = f.admin_id
            WHERE u.role = 'admin'
            GROUP BY u.id, u.first_name, u.last_name
            ORDER BY feedback_count DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Check if admin has given feedback for idea
     */
    public function hasGivenFeedback($ideaId, $adminId)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM {$this->table} WHERE idea_id = ? AND admin_id = ?");
        $stmt->execute([$ideaId, $adminId]);
        $result = $stmt->fetch();
        
        return $result['count'] > 0;
    }
    
    /**
     * Get ideas without feedback
     */
    public function getIdeasWithoutFeedback($limit = 20)
    {
        $stmt = $this->db->prepare("
            SELECT 
                i.id,
                i.title,
                i.status,
                i.created_at,
                u.first_name,
                u.last_name,
                t.name as theme_name
            FROM ideas i
            JOIN users u ON i.user_id = u.id
            JOIN themes t ON i.theme_id = t.id
            LEFT JOIN {$this->table} f ON i.id = f.idea_id
            WHERE f.id IS NULL
            AND i.status IN ('under_review', 'accepted', 'rejected')
            ORDER BY i.created_at ASC
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }
    
    /**
     * Search feedback by message content
     */
    public function search($query, $page = 1, $perPage = 20)
    {
        $offset = ($page - 1) * $perPage;
        
        $stmt = $this->db->prepare("
            SELECT 
                f.*,
                i.title as idea_title,
                u_admin.first_name as admin_first_name,
                u_admin.last_name as admin_last_name,
                u_author.first_name as author_first_name,
                u_author.last_name as author_last_name
            FROM {$this->table} f
            JOIN ideas i ON f.idea_id = i.id
            JOIN users u_admin ON f.admin_id = u_admin.id
            JOIN users u_author ON i.user_id = u_author.id
            WHERE f.message LIKE ?
            ORDER BY f.created_at DESC
            LIMIT ? OFFSET ?
        ");
        
        $stmt->execute(["%{$query}%", $perPage, $offset]);
        return $stmt->fetchAll();
    }

    /**
     * Count feedback by user (for evaluators)
     */
    public function countByUser($userId)
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(DISTINCT f.idea_id) as count 
            FROM {$this->table} f
            JOIN evaluations e ON f.idea_id = e.idea_id
            WHERE e.evaluator_id = ?
        ");
        $stmt->execute([$userId]);
        return $stmt->fetch()['count'];
    }
}
