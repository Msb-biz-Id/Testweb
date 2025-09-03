<?php

namespace App\Models;

/**
 * Page Model
 * Handles dynamic page content with drag-and-drop editor
 */
class Page extends Model
{
    protected $table = 'pages';
    protected $fillable = [
        'title', 'slug', 'content', 'meta_title', 'meta_description',
        'is_homepage', 'is_published'
    ];

    /**
     * Get published pages
     */
    public function getPublished()
    {
        return $this->where('is_published', 1);
    }

    /**
     * Get page by slug
     */
    public function findBySlug($slug)
    {
        return $this->whereFirst('slug', $slug);
    }

    /**
     * Get homepage
     */
    public function getHomepage()
    {
        return $this->whereFirst('is_homepage', 1);
    }

    /**
     * Set homepage
     */
    public function setHomepage($id)
    {
        // Remove homepage flag from all pages
        $this->db->query("UPDATE {$this->table} SET is_homepage = 0");
        
        // Set new homepage
        return $this->update($id, ['is_homepage' => 1]);
    }

    /**
     * Create page with slug generation
     */
    public function createPage($data)
    {
        if (!isset($data['slug']) || empty($data['slug'])) {
            $data['slug'] = $this->generateSlug($data['title']);
        }
        
        // If this is set as homepage, remove homepage flag from others
        if (isset($data['is_homepage']) && $data['is_homepage']) {
            $this->db->query("UPDATE {$this->table} SET is_homepage = 0");
        }
        
        return $this->create($data);
    }

    /**
     * Update page with slug generation
     */
    public function updatePage($id, $data)
    {
        if (isset($data['title']) && (!isset($data['slug']) || empty($data['slug']))) {
            $data['slug'] = $this->generateSlug($data['title'], $id);
        }
        
        // If this is set as homepage, remove homepage flag from others
        if (isset($data['is_homepage']) && $data['is_homepage']) {
            $this->db->query("UPDATE {$this->table} SET is_homepage = 0 WHERE id != ?", [$id]);
        }
        
        return $this->update($id, $data);
    }

    /**
     * Get page content as array
     */
    public function getContentArray($pageId)
    {
        $page = $this->find($pageId);
        if ($page && $page['content']) {
            return json_decode($page['content'], true);
        }
        return [];
    }

    /**
     * Update page content
     */
    public function updateContent($pageId, $content)
    {
        $contentJson = is_array($content) ? json_encode($content) : $content;
        return $this->update($pageId, ['content' => $contentJson]);
    }
}