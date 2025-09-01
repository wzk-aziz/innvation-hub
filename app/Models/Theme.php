<?php

namespace App\Models;

use App\Core\Database;

/**
 * Theme Model
 * Handles theme-related database operations
 */
class Theme
{
    private $db;
    private $table = 'themes';
    
    public function __construct()
    {
        $this->db = Database::getInstance();
    }
    
    /**
     * Find theme by ID
     */
    public function findById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    /**
     * Find theme by name
     */
    public function findByName($name)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE name = ?");
        $stmt->execute([$name]);
        return $stmt->fetch();
    }
    
    /**
     * Get all themes
     */
    public function getAll($activeOnly = false)
    {
        $sql = "SELECT * FROM {$this->table}";
        
        if ($activeOnly) {
            $sql .= " WHERE is_active = 1";
        }
        
        $sql .= " ORDER BY name";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Get all themes with pagination and search
     */
    public function getAllPaginated($page = 1, $perPage = 20, $search = '', $status = '')
    {
        $offset = ($page - 1) * $perPage;
        
        $sql = "SELECT t.*, 
                COUNT(i.id) as ideas_count,
                COUNT(CASE WHEN i.status = 'submitted' THEN 1 END) as pending_ideas,
                COUNT(CASE WHEN i.status = 'accepted' THEN 1 END) as accepted_ideas
                FROM {$this->table} t 
                LEFT JOIN ideas i ON t.id = i.theme_id
                WHERE 1=1";
        $params = [];
        
        if (!empty($search)) {
            $sql .= " AND (t.name LIKE ? OR t.description LIKE ?)";
            $searchParam = '%' . $search . '%';
            $params[] = $searchParam;
            $params[] = $searchParam;
        }
        
        if ($status === 'active') {
            $sql .= " AND t.is_active = 1";
        } elseif ($status === 'inactive') {
            $sql .= " AND t.is_active = 0";
        }
        
        $sql .= " GROUP BY t.id ORDER BY t.name LIMIT ? OFFSET ?";
        $params[] = $perPage;
        $params[] = $offset;
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    /**
     * Count all themes with optional filters
     */
    public function countAll($search = '', $status = '')
    {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE 1=1";
        $params = [];
        
        if (!empty($search)) {
            $sql .= " AND (name LIKE ? OR description LIKE ?)";
            $searchParam = '%' . $search . '%';
            $params[] = $searchParam;
            $params[] = $searchParam;
        }
        
        if ($status === 'active') {
            $sql .= " AND is_active = 1";
        } elseif ($status === 'inactive') {
            $sql .= " AND is_active = 0";
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }
    
    /**
     * Count active themes
     */
    public function countActive()
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$this->table} WHERE is_active = 1");
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    
    /**
     * Get ideas count per theme
     */
    public function getIdeasCount()
    {
        $stmt = $this->db->prepare("
            SELECT t.id, t.name, COUNT(i.id) as ideas_count
            FROM {$this->table} t
            LEFT JOIN ideas i ON t.id = i.theme_id
            GROUP BY t.id, t.name
            ORDER BY ideas_count DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Get most popular themes
     */
    public function getMostPopular($limit = 10)
    {
        $stmt = $this->db->prepare("
            SELECT t.id, t.name, COUNT(i.id) as ideas_count
            FROM {$this->table} t
            LEFT JOIN ideas i ON t.id = i.theme_id
            WHERE t.is_active = 1
            GROUP BY t.id, t.name
            ORDER BY ideas_count DESC
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }
    
    /**
     * Get active themes
     */
    public function getActive()
    {
        return $this->getAll(true);
    }
    
    /**
     * Count total themes
     */
    public function count()
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM {$this->table}");
        $stmt->execute();
        $result = $stmt->fetch();
        return (int) $result['count'];
    }
    
    /**
     * Create new theme
     */
    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} (name, description, is_active) VALUES (?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            $data['name'],
            $data['description'] ?? null,
            $data['is_active'] ?? 1
        ]);
        
        if ($result) {
            return $this->db->lastInsertId();
        }
        
        return false;
    }
    
    /**
     * Update theme
     */
    public function update($id, $data)
    {
        $fields = [];
        $params = [];
        
        $allowedFields = ['name', 'description', 'is_active'];
        
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
        return $stmt->execute($params);
    }
    
    /**
     * Delete theme
     */
    public function delete($id)
    {
        // Check if theme has associated ideas
        if ($this->hasIdeas($id)) {
            return false; // Cannot delete theme with associated ideas
        }
        
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    /**
     * Check if theme name exists
     */
    public function nameExists($name, $excludeId = null)
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE name = ?";
        $params = [$name];
        
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        
        return $result['count'] > 0;
    }
    
    /**
     * Check if theme has associated ideas
     */
    public function hasIdeas($id)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM ideas WHERE theme_id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        
        return $result['count'] > 0;
    }
    
    /**
     * Get theme statistics
     */
    public function getStatistics()
    {
        $stmt = $this->db->prepare("
            SELECT 
                t.id,
                t.name,
                t.is_active,
                COUNT(i.id) as ideas_count,
                AVG(i.avg_score) as avg_score
            FROM {$this->table} t
            LEFT JOIN ideas i ON t.id = i.theme_id
            GROUP BY t.id, t.name, t.is_active
            ORDER BY t.name
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Toggle theme active status
     */
    public function toggleActive($id)
    {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET is_active = !is_active WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    /**
     * Search themes
     */
    public function search($query)
    {
        $sql = "SELECT * FROM {$this->table} WHERE 
                (name LIKE ? OR description LIKE ?)
                ORDER BY name";
        
        $params = ["%{$query}%", "%{$query}%"];
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    /**
     * Get themes with idea counts
     */
    public function getWithIdeaCounts()
    {
        $stmt = $this->db->prepare("
            SELECT 
                t.*,
                COUNT(i.id) as ideas_count,
                COUNT(CASE WHEN i.status = 'submitted' THEN 1 END) as submitted_count,
                COUNT(CASE WHEN i.status = 'under_review' THEN 1 END) as under_review_count,
                COUNT(CASE WHEN i.status = 'accepted' THEN 1 END) as accepted_count,
                COUNT(CASE WHEN i.status = 'rejected' THEN 1 END) as rejected_count
            FROM {$this->table} t
            LEFT JOIN ideas i ON t.id = i.theme_id
            GROUP BY t.id
            ORDER BY t.name
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
