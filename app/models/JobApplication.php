<?php

namespace App\Models;

use App\Core\Database;

/**
 * Job Application Model
 * Handles job application operations
 */
class JobApplication
{
    private $db;
    
    public function __construct()
    {
        $this->db = Database::getInstance();
    }
    
    /**
     * Get all applications with optional filters
     */
    public function getAll($filters = [])
    {
        $sql = "SELECT ja.*, c.title as career_title, c.department 
                FROM job_applications ja 
                LEFT JOIN careers c ON ja.career_id = c.id 
                WHERE 1=1";
        $params = [];
        
        if (!empty($filters['career_id'])) {
            $sql .= " AND ja.career_id = :career_id";
            $params['career_id'] = $filters['career_id'];
        }
        
        if (!empty($filters['status'])) {
            $sql .= " AND ja.status = :status";
            $params['status'] = $filters['status'];
        }
        
        if (!empty($filters['search'])) {
            $sql .= " AND (ja.full_name LIKE :search OR ja.email LIKE :search OR c.title LIKE :search)";
            $params['search'] = '%' . $filters['search'] . '%';
        }
        
        $sql .= " ORDER BY ja.applied_at DESC";
        
        if (!empty($filters['limit'])) {
            $sql .= " LIMIT :limit";
            $params['limit'] = (int)$filters['limit'];
        }
        
        if (!empty($filters['offset'])) {
            $sql .= " OFFSET :offset";
            $params['offset'] = (int)$filters['offset'];
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Get application by ID
     */
    public function getById($id)
    {
        $stmt = $this->db->prepare("
            SELECT ja.*, c.title as career_title, c.department, c.location 
            FROM job_applications ja 
            LEFT JOIN careers c ON ja.career_id = c.id 
            WHERE ja.id = :id
        ");
        $stmt->execute(['id' => $id]);
        
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Create new application
     */
    public function create($data)
    {
        $sql = "INSERT INTO job_applications (
            career_id, full_name, email, phone, cover_letter, resume_file,
            portfolio_url, linkedin_url, expected_salary, availability_date, status
        ) VALUES (
            :career_id, :full_name, :email, :phone, :cover_letter, :resume_file,
            :portfolio_url, :linkedin_url, :expected_salary, :availability_date, :status
        )";
        
        $stmt = $this->db->prepare($sql);
        
        $result = $stmt->execute([
            'career_id' => $data['career_id'],
            'full_name' => $data['full_name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'cover_letter' => $data['cover_letter'] ?? null,
            'resume_file' => $data['resume_file'] ?? null,
            'portfolio_url' => $data['portfolio_url'] ?? null,
            'linkedin_url' => $data['linkedin_url'] ?? null,
            'expected_salary' => $data['expected_salary'] ?? null,
            'availability_date' => $data['availability_date'] ?? null,
            'status' => $data['status'] ?? 'pending'
        ]);
        
        if ($result) {
            // Update application count for the career
            $this->updateApplicationCount($data['career_id']);
        }
        
        return $result;
    }
    
    /**
     * Update application
     */
    public function update($id, $data)
    {
        $sql = "UPDATE job_applications SET 
            status = :status, notes = :notes, updated_at = CURRENT_TIMESTAMP
            WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            'id' => $id,
            'status' => $data['status'],
            'notes' => $data['notes'] ?? null
        ]);
    }
    
    /**
     * Delete application
     */
    public function delete($id)
    {
        // Get career_id before deleting
        $application = $this->getById($id);
        
        $stmt = $this->db->prepare("DELETE FROM job_applications WHERE id = :id");
        $result = $stmt->execute(['id' => $id]);
        
        if ($result && $application) {
            // Update application count for the career
            $this->updateApplicationCount($application['career_id']);
        }
        
        return $result;
    }
    
    /**
     * Get applications by career
     */
    public function getByCareer($careerId, $limit = null)
    {
        $sql = "SELECT * FROM job_applications WHERE career_id = :career_id ORDER BY applied_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT :limit";
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':career_id', $careerId);
        
        if ($limit) {
            $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        }
        
        $stmt->execute();
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Get application statistics
     */
    public function getStats()
    {
        $stats = [];
        
        // Total applications
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM job_applications");
        $stmt->execute();
        $stats['total'] = $stmt->fetch(\PDO::FETCH_ASSOC)['total'];
        
        // Applications by status
        $stmt = $this->db->prepare("
            SELECT status, COUNT(*) as count 
            FROM job_applications 
            GROUP BY status 
            ORDER BY count DESC
        ");
        $stmt->execute();
        $stats['by_status'] = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        // Recent applications (last 30 days)
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as recent 
            FROM job_applications 
            WHERE applied_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
        ");
        $stmt->execute();
        $stats['recent'] = $stmt->fetch(\PDO::FETCH_ASSOC)['recent'];
        
        // Applications by career
        $stmt = $this->db->prepare("
            SELECT c.title, COUNT(ja.id) as count 
            FROM careers c 
            LEFT JOIN job_applications ja ON c.id = ja.career_id 
            GROUP BY c.id, c.title 
            ORDER BY count DESC 
            LIMIT 10
        ");
        $stmt->execute();
        $stats['by_career'] = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        // Monthly applications (last 12 months)
        $stmt = $this->db->prepare("
            SELECT 
                DATE_FORMAT(applied_at, '%Y-%m') as month,
                COUNT(*) as count 
            FROM job_applications 
            WHERE applied_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
            GROUP BY DATE_FORMAT(applied_at, '%Y-%m')
            ORDER BY month DESC
        ");
        $stmt->execute();
        $stats['monthly'] = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        return $stats;
    }
    
    /**
     * Update application count for career
     */
    private function updateApplicationCount($careerId)
    {
        $stmt = $this->db->prepare("
            UPDATE careers 
            SET application_count = (
                SELECT COUNT(*) 
                FROM job_applications 
                WHERE career_id = :career_id
            ) 
            WHERE id = :career_id
        ");
        $stmt->execute(['career_id' => $careerId]);
    }
    
    /**
     * Get recent applications
     */
    public function getRecent($limit = 10)
    {
        $stmt = $this->db->prepare("
            SELECT ja.*, c.title as career_title, c.department 
            FROM job_applications ja 
            LEFT JOIN careers c ON ja.career_id = c.id 
            ORDER BY ja.applied_at DESC 
            LIMIT :limit
        ");
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Check if email already applied for career
     */
    public function hasApplied($email, $careerId)
    {
        $stmt = $this->db->prepare("
            SELECT id FROM job_applications 
            WHERE email = :email AND career_id = :career_id
        ");
        $stmt->execute([
            'email' => $email,
            'career_id' => $careerId
        ]);
        
        return $stmt->fetch() !== false;
    }
    
    /**
     * Get applications by date range
     */
    public function getByDateRange($startDate, $endDate)
    {
        $stmt = $this->db->prepare("
            SELECT ja.*, c.title as career_title, c.department 
            FROM job_applications ja 
            LEFT JOIN careers c ON ja.career_id = c.id 
            WHERE ja.applied_at BETWEEN :start_date AND :end_date
            ORDER BY ja.applied_at DESC
        ");
        $stmt->execute([
            'start_date' => $startDate,
            'end_date' => $endDate
        ]);
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}