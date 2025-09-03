<?php

namespace App\Models;

/**
 * Testimonial Model
 * Handles client testimonials
 */
class Testimonial extends Model
{
    protected $table = 'testimonials';
    protected $fillable = [
        'client_name', 'client_position', 'client_company', 'testimonial',
        'client_photo', 'rating', 'is_featured', 'is_published', 'sort_order'
    ];

    /**
     * Get published testimonials
     */
    public function getPublished()
    {
        return $this->where('is_published', 1);
    }

    /**
     * Get featured testimonials
     */
    public function getFeatured($limit = null)
    {
        $sql = "SELECT * FROM {$this->table} WHERE is_featured = 1 AND is_published = 1 ORDER BY sort_order ASC, created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }
        
        return $this->db->fetchAll($sql);
    }

    /**
     * Get testimonials ordered by sort order
     */
    public function getOrdered()
    {
        $sql = "SELECT * FROM {$this->table} WHERE is_published = 1 ORDER BY sort_order ASC, created_at DESC";
        return $this->db->fetchAll($sql);
    }

    /**
     * Update sort order
     */
    public function updateSortOrder($id, $sortOrder)
    {
        return $this->update($id, ['sort_order' => $sortOrder]);
    }

    /**
     * Bulk update sort orders
     */
    public function updateSortOrders($sortData)
    {
        $this->db->beginTransaction();
        
        try {
            foreach ($sortData as $item) {
                $this->update($item['id'], ['sort_order' => $item['sort_order']]);
            }
            
            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollback();
            return false;
        }
    }
}