<?php

namespace App\Models;

/**
 * Category Model
 * Handles post categories
 */
class Category extends Model
{
    protected $table = 'categories';
    protected $fillable = ['name', 'slug', 'description'];

    /**
     * Get category by slug
     */
    public function findBySlug($slug)
    {
        return $this->whereFirst('slug', $slug);
    }

    /**
     * Create category with slug generation
     */
    public function createCategory($data)
    {
        if (!isset($data['slug']) || empty($data['slug'])) {
            $data['slug'] = $this->generateSlug($data['name']);
        }
        
        return $this->create($data);
    }

    /**
     * Update category with slug generation
     */
    public function updateCategory($id, $data)
    {
        if (isset($data['name']) && (!isset($data['slug']) || empty($data['slug']))) {
            $data['slug'] = $this->generateSlug($data['name'], $id);
        }
        
        return $this->update($id, $data);
    }

    /**
     * Get categories with post count
     */
    public function getWithPostCount()
    {
        $sql = "SELECT c.*, COUNT(p.id) as post_count 
                FROM {$this->table} c 
                LEFT JOIN posts p ON c.id = p.category_id AND p.is_published = 1 
                GROUP BY c.id 
                ORDER BY c.name ASC";
        
        return $this->db->fetchAll($sql);
    }
}