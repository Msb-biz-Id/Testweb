<?php

namespace App\Models;

use App\Core\Database;

/**
 * Career Model
 * Handles career/job posting operations
 */
class Career
{
    private $db;
    
    public function __construct()
    {
        $this->db = Database::getInstance();
    }
    
    /**
     * Get all careers with optional filters
     */
    public function getAll($filters = [])
    {
        $sql = "SELECT * FROM careers WHERE 1=1";
        $params = [];
        
        if (!empty($filters['status'])) {
            $sql .= " AND status = :status";
            $params['status'] = $filters['status'];
        }
        
        if (!empty($filters['employment_type'])) {
            $sql .= " AND employment_type = :employment_type";
            $params['employment_type'] = $filters['employment_type'];
        }
        
        if (!empty($filters['department'])) {
            $sql .= " AND department = :department";
            $params['department'] = $filters['department'];
        }
        
        if (!empty($filters['experience_level'])) {
            $sql .= " AND experience_level = :experience_level";
            $params['experience_level'] = $filters['experience_level'];
        }
        
        if (!empty($filters['featured'])) {
            $sql .= " AND featured = :featured";
            $params['featured'] = $filters['featured'];
        }
        
        if (!empty($filters['search'])) {
            $sql .= " AND (title LIKE :search OR description LIKE :search OR department LIKE :search)";
            $params['search'] = '%' . $filters['search'] . '%';
        }
        
        $sql .= " ORDER BY featured DESC, created_at DESC";
        
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
     * Get career by ID
     */
    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM careers WHERE id = :id");
        $stmt->execute(['id' => $id]);
        
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Get career by slug
     */
    public function getBySlug($slug)
    {
        $stmt = $this->db->prepare("SELECT * FROM careers WHERE slug = :slug AND status = 'active'");
        $stmt->execute(['slug' => $slug]);
        
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Create new career
     */
    public function create($data)
    {
        $sql = "INSERT INTO careers (
            title, slug, description, requirements, responsibilities, benefits,
            employment_type, experience_level, department, location,
            salary_min, salary_max, currency, application_deadline, start_date,
            status, featured, meta_title, meta_description, meta_keywords
        ) VALUES (
            :title, :slug, :description, :requirements, :responsibilities, :benefits,
            :employment_type, :experience_level, :department, :location,
            :salary_min, :salary_max, :currency, :application_deadline, :start_date,
            :status, :featured, :meta_title, :meta_description, :meta_keywords
        )";
        
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            'title' => $data['title'],
            'slug' => $data['slug'],
            'description' => $data['description'],
            'requirements' => $data['requirements'],
            'responsibilities' => $data['responsibilities'],
            'benefits' => $data['benefits'] ?? null,
            'employment_type' => $data['employment_type'],
            'experience_level' => $data['experience_level'],
            'department' => $data['department'],
            'location' => $data['location'],
            'salary_min' => $data['salary_min'] ?? null,
            'salary_max' => $data['salary_max'] ?? null,
            'currency' => $data['currency'] ?? 'IDR',
            'application_deadline' => $data['application_deadline'] ?? null,
            'start_date' => $data['start_date'] ?? null,
            'status' => $data['status'] ?? 'active',
            'featured' => $data['featured'] ?? 0,
            'meta_title' => $data['meta_title'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
            'meta_keywords' => $data['meta_keywords'] ?? null
        ]);
    }
    
    /**
     * Update career
     */
    public function update($id, $data)
    {
        $sql = "UPDATE careers SET 
            title = :title, slug = :slug, description = :description, 
            requirements = :requirements, responsibilities = :responsibilities, 
            benefits = :benefits, employment_type = :employment_type, 
            experience_level = :experience_level, department = :department, 
            location = :location, salary_min = :salary_min, salary_max = :salary_max, 
            currency = :currency, application_deadline = :application_deadline, 
            start_date = :start_date, status = :status, featured = :featured,
            meta_title = :meta_title, meta_description = :meta_description, 
            meta_keywords = :meta_keywords, updated_at = CURRENT_TIMESTAMP
            WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            'id' => $id,
            'title' => $data['title'],
            'slug' => $data['slug'],
            'description' => $data['description'],
            'requirements' => $data['requirements'],
            'responsibilities' => $data['responsibilities'],
            'benefits' => $data['benefits'] ?? null,
            'employment_type' => $data['employment_type'],
            'experience_level' => $data['experience_level'],
            'department' => $data['department'],
            'location' => $data['location'],
            'salary_min' => $data['salary_min'] ?? null,
            'salary_max' => $data['salary_max'] ?? null,
            'currency' => $data['currency'] ?? 'IDR',
            'application_deadline' => $data['application_deadline'] ?? null,
            'start_date' => $data['start_date'] ?? null,
            'status' => $data['status'] ?? 'active',
            'featured' => $data['featured'] ?? 0,
            'meta_title' => $data['meta_title'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
            'meta_keywords' => $data['meta_keywords'] ?? null
        ]);
    }
    
    /**
     * Delete career
     */
    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM careers WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
    
    /**
     * Increment view count
     */
    public function incrementViews($id)
    {
        $stmt = $this->db->prepare("UPDATE careers SET views = views + 1 WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
    
    /**
     * Get featured careers
     */
    public function getFeatured($limit = 3)
    {
        $stmt = $this->db->prepare("
            SELECT * FROM careers 
            WHERE status = 'active' AND featured = 1 
            ORDER BY created_at DESC 
            LIMIT :limit
        ");
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Get careers by department
     */
    public function getByDepartment($department, $limit = null)
    {
        $sql = "SELECT * FROM careers WHERE status = 'active' AND department = :department ORDER BY created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT :limit";
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':department', $department);
        
        if ($limit) {
            $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        }
        
        $stmt->execute();
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Get career statistics
     */
    public function getStats()
    {
        $stats = [];
        
        // Total careers
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM careers");
        $stmt->execute();
        $stats['total'] = $stmt->fetch(\PDO::FETCH_ASSOC)['total'];
        
        // Active careers
        $stmt = $this->db->prepare("SELECT COUNT(*) as active FROM careers WHERE status = 'active'");
        $stmt->execute();
        $stats['active'] = $stmt->fetch(\PDO::FETCH_ASSOC)['active'];
        
        // Featured careers
        $stmt = $this->db->prepare("SELECT COUNT(*) as featured FROM careers WHERE featured = 1 AND status = 'active'");
        $stmt->execute();
        $stats['featured'] = $stmt->fetch(\PDO::FETCH_ASSOC)['featured'];
        
        // Total applications
        $stmt = $this->db->prepare("SELECT COUNT(*) as applications FROM job_applications");
        $stmt->execute();
        $stats['applications'] = $stmt->fetch(\PDO::FETCH_ASSOC)['applications'];
        
        // Careers by department
        $stmt = $this->db->prepare("
            SELECT department, COUNT(*) as count 
            FROM careers 
            WHERE status = 'active' 
            GROUP BY department 
            ORDER BY count DESC
        ");
        $stmt->execute();
        $stats['by_department'] = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        // Careers by employment type
        $stmt = $this->db->prepare("
            SELECT employment_type, COUNT(*) as count 
            FROM careers 
            WHERE status = 'active' 
            GROUP BY employment_type 
            ORDER BY count DESC
        ");
        $stmt->execute();
        $stats['by_employment_type'] = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        return $stats;
    }
    
    /**
     * Search careers
     */
    public function search($query, $filters = [])
    {
        $sql = "SELECT * FROM careers WHERE status = 'active' AND (
            title LIKE :query OR 
            description LIKE :query OR 
            department LIKE :query OR 
            location LIKE :query
        )";
        
        $params = ['query' => '%' . $query . '%'];
        
        if (!empty($filters['employment_type'])) {
            $sql .= " AND employment_type = :employment_type";
            $params['employment_type'] = $filters['employment_type'];
        }
        
        if (!empty($filters['experience_level'])) {
            $sql .= " AND experience_level = :experience_level";
            $params['experience_level'] = $filters['experience_level'];
        }
        
        if (!empty($filters['department'])) {
            $sql .= " AND department = :department";
            $params['department'] = $filters['department'];
        }
        
        $sql .= " ORDER BY featured DESC, created_at DESC";
        
        if (!empty($filters['limit'])) {
            $sql .= " LIMIT :limit";
            $params['limit'] = (int)$filters['limit'];
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Generate unique slug
     */
    public function generateSlug($title, $excludeId = null)
    {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
        $originalSlug = $slug;
        $counter = 1;
        
        while (true) {
            $sql = "SELECT id FROM careers WHERE slug = :slug";
            $params = ['slug' => $slug];
            
            if ($excludeId) {
                $sql .= " AND id != :exclude_id";
                $params['exclude_id'] = $excludeId;
            }
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            
            if (!$stmt->fetch()) {
                break;
            }
            
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }
}