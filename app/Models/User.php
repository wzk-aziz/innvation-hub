<?php

namespace App\Models;

use App\Core\Database;

/**
 * User Model
 * Handles user-related database operations
 */
class User
{
    private $db;
    private $table = 'users';
    
    public function __construct()
    {
        $this->db = Database::getInstance();
    }
    
    /**
     * Find user by ID
     */
    public function findById($id)
    {
        // Cast ID to integer to ensure proper comparison
        $id = (int) $id;
        
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    /**
     * Find user by email
     */
    public function findByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }
    
    /**
     * Get all users with pagination and search
     */
    public function getAllPaginated($page = 1, $perPage = 20, $search = '', $role = '')
    {
        $offset = ($page - 1) * $perPage;
        
        $sql = "SELECT * FROM {$this->table} WHERE 1=1";
        $params = [];
        
        if (!empty($search)) {
            $sql .= " AND (first_name LIKE ? OR last_name LIKE ? OR email LIKE ?)";
            $searchParam = '%' . $search . '%';
            $params[] = $searchParam;
            $params[] = $searchParam;
            $params[] = $searchParam;
        }
        
        if (!empty($role)) {
            $sql .= " AND role = ?";
            $params[] = $role;
        }
        
        $sql .= " ORDER BY created_at DESC LIMIT ? OFFSET ?";
        $params[] = $perPage;
        $params[] = $offset;
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    /**
     * Count all users with optional filters
     */
    public function countAll($search = '', $role = '')
    {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE 1=1";
        $params = [];
        
        if (!empty($search)) {
            $sql .= " AND (first_name LIKE ? OR last_name LIKE ? OR email LIKE ?)";
            $searchParam = '%' . $search . '%';
            $params[] = $searchParam;
            $params[] = $searchParam;
            $params[] = $searchParam;
        }
        
        if (!empty($role)) {
            $sql .= " AND role = ?";
            $params[] = $role;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }
    
    /**
     * Check if email exists (excluding specific user ID)
     */
    public function emailExists($email, $excludeId = null)
    {
        $sql = "SELECT id FROM {$this->table} WHERE email = ?";
        $params = [$email];
        
        if ($excludeId !== null) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() !== false;
    }
    
    /**
     * Create a new user
     */
    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} (first_name, last_name, email, password, role, created_at) 
                VALUES (?, ?, ?, ?, ?, NOW())";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['first_name'],
            $data['last_name'],
            $data['email'],
            $data['password'],
            $data['role']
        ]);
    }
    
    /**
     * Update user
     */
    public function update($id, $data)
    {
        $fields = [];
        $params = [];
        
        foreach ($data as $key => $value) {
            if ($key !== 'id') {
                $fields[] = "{$key} = ?";
                $params[] = $value;
            }
        }
        
        if (empty($fields)) {
            return false;
        }
        
        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = ?";
        $params[] = $id;
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }
    
    /**
     * Delete user
     */
    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    /**
     * Count users by role
     */
    public function countByRole()
    {
        $stmt = $this->db->prepare("SELECT role, COUNT(*) as count FROM {$this->table} GROUP BY role");
        $stmt->execute();
        $results = $stmt->fetchAll();
        
        $counts = [];
        foreach ($results as $result) {
            $counts[$result['role']] = $result['count'];
        }
        
        return $counts;
    }
    
    /**
     * Get recent registrations
     */
    public function getRecentRegistrations($days = 30)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$this->table} WHERE created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)");
        $stmt->execute([$days]);
        return $stmt->fetchColumn();
    }
    
    /**
     * Get active users (users who have submitted ideas recently)
     */
    public function getActiveUsers($days = 30)
    {
        $sql = "SELECT COUNT(DISTINCT u.id) 
                FROM {$this->table} u 
                INNER JOIN ideas i ON u.id = i.user_id 
                WHERE i.created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$days]);
        return $stmt->fetchColumn();
    }
    
    /**
     * Get all users (for reports/exports)
     */
    public function getAll()
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Count users
     */
    public function count($role = null)
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        $params = [];
        
        if ($role) {
            $sql .= " WHERE role = ?";
            $params[] = $role;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return $result['total'];
    }
    
    /**
     * Get users by role
     */
    public function getByRole($role)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE role = ? AND status = 'active' ORDER BY first_name, last_name");
        $stmt->execute([$role]);
        return $stmt->fetchAll();
    }
    
    /**
     * Get user statistics
     */
    public function getStatistics()
    {
        $stmt = $this->db->prepare("
            SELECT 
                role,
                status,
                COUNT(*) as count
            FROM {$this->table} 
            GROUP BY role, status
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Search users
     */
    public function search($query, $role = null)
    {
        $sql = "SELECT * FROM {$this->table} WHERE 
                (first_name LIKE ? OR last_name LIKE ? OR email LIKE ?)";
        $params = ["%{$query}%", "%{$query}%", "%{$query}%"];
        
        if ($role) {
            $sql .= " AND role = ?";
            $params[] = $role;
        }
        
        $sql .= " ORDER BY first_name, last_name";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    /**
     * Get recent users
     */
    public function getRecent($limit = 10)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM {$this->table}
            ORDER BY created_at DESC
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }
}
