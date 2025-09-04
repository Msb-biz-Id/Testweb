<?php

namespace App\Models;

/**
 * Tag Model
 * Handles post tags
 */
class Tag extends Model
{
    protected $table = 'tags';
    protected $fillable = ['name', 'slug'];

    /**
     * Get tag by slug
     */
    public function findBySlug($slug)
    {
        return $this->whereFirst('slug', $slug);
    }

    /**
     * Create tag with slug generation
     */
    public function createTag($data)
    {
        if (!isset($data['slug']) || empty($data['slug'])) {
            $data['slug'] = $this->generateSlug($data['name']);
        }
        
        return $this->create($data);
    }

    /**
     * Update tag with slug generation
     */
    public function updateTag($id, $data)
    {
        if (isset($data['name']) && (!isset($data['slug']) || empty($data['slug']))) {
            $data['slug'] = $this->generateSlug($data['name'], $id);
        }
        
        return $this->update($id, $data);
    }

    /**
     * Get tags with post count
     */
    public function getWithPostCount()
    {
        $sql = "SELECT t.*, COUNT(pt.post_id) as post_count 
                FROM {$this->table} t 
                LEFT JOIN post_tags pt ON t.id = pt.tag_id 
                LEFT JOIN posts p ON pt.post_id = p.id AND p.is_published = 1 
                GROUP BY t.id 
                ORDER BY t.name ASC";
        
        return $this->db->fetchAll($sql);
    }

    /**
     * Find or create tag
     */
    public function findOrCreate($name)
    {
        $tag = $this->whereFirst('name', $name);
        
        if (!$tag) {
            $id = $this->createTag(['name' => $name]);
            $tag = $this->find($id);
        }
        
        return $tag;
    }
}