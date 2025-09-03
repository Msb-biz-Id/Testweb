<?php

namespace App\Models;

/**
 * Post Model
 * Handles blog post data and operations
 */
class Post extends Model
{
    protected $table = 'posts';
    protected $fillable = [
        'title', 'slug', 'content', 'excerpt', 'featured_image', 'category_id',
        'author_id', 'is_published', 'published_at', 'meta_title', 'meta_description'
    ];

    /**
     * Get published posts
     */
    public function getPublished()
    {
        return $this->where('is_published', 1);
    }

    /**
     * Get post by slug
     */
    public function findBySlug($slug)
    {
        return $this->whereFirst('slug', $slug);
    }

    /**
     * Get posts with author and category info
     */
    public function getWithDetails($limit = null, $offset = 0)
    {
        $sql = "SELECT p.*, u.full_name as author_name, c.name as category_name 
                FROM {$this->table} p 
                LEFT JOIN users u ON p.author_id = u.id 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.is_published = 1 
                ORDER BY p.published_at DESC, p.created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT {$limit} OFFSET {$offset}";
        }
        
        return $this->db->fetchAll($sql);
    }

    /**
     * Get posts with pagination
     */
    public function getPaginated($page = 1, $perPage = 10, $filters = [])
    {
        $sql = "SELECT p.*, u.full_name as author_name, c.name as category_name 
                FROM {$this->table} p 
                LEFT JOIN users u ON p.author_id = u.id 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.is_published = 1";
        $params = [];
        
        // Apply filters
        if (isset($filters['category']) && !empty($filters['category'])) {
            $sql .= " AND p.category_id = ?";
            $params[] = $filters['category'];
        }
        
        if (isset($filters['search']) && !empty($filters['search'])) {
            $sql .= " AND (p.title LIKE ? OR p.content LIKE ? OR p.excerpt LIKE ?)";
            $searchTerm = "%{$filters['search']}%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        $sql .= " ORDER BY p.published_at DESC, p.created_at DESC";
        
        $offset = ($page - 1) * $perPage;
        $sql .= " LIMIT {$perPage} OFFSET {$offset}";
        
        $data = $this->db->fetchAll($sql, $params);
        
        // Get total count
        $countSql = "SELECT COUNT(*) as total FROM {$this->table} p WHERE p.is_published = 1";
        $countParams = [];
        
        if (isset($filters['category']) && !empty($filters['category'])) {
            $countSql .= " AND p.category_id = ?";
            $countParams[] = $filters['category'];
        }
        
        if (isset($filters['search']) && !empty($filters['search'])) {
            $countSql .= " AND (p.title LIKE ? OR p.content LIKE ? OR p.excerpt LIKE ?)";
            $searchTerm = "%{$filters['search']}%";
            $countParams[] = $searchTerm;
            $countParams[] = $searchTerm;
            $countParams[] = $searchTerm;
        }
        
        $total = $this->db->fetch($countSql, $countParams)['total'];
        
        return [
            'data' => $data,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => ceil($total / $perPage),
            'from' => $offset + 1,
            'to' => min($offset + $perPage, $total)
        ];
    }

    /**
     * Search posts
     */
    public function search($query, $limit = null)
    {
        $sql = "SELECT p.*, u.full_name as author_name, c.name as category_name 
                FROM {$this->table} p 
                LEFT JOIN users u ON p.author_id = u.id 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.is_published = 1 AND (
                    p.title LIKE ? OR 
                    p.content LIKE ? OR 
                    p.excerpt LIKE ?
                ) ORDER BY p.published_at DESC";
        
        $params = ["%{$query}%", "%{$query}%", "%{$query}%"];
        
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }
        
        return $this->db->fetchAll($sql, $params);
    }

    /**
     * Get recent posts
     */
    public function getRecent($limit = 5)
    {
        return $this->getWithDetails($limit);
    }

    /**
     * Get posts by category
     */
    public function getByCategory($categoryId, $limit = null)
    {
        $sql = "SELECT p.*, u.full_name as author_name, c.name as category_name 
                FROM {$this->table} p 
                LEFT JOIN users u ON p.author_id = u.id 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.is_published = 1 AND p.category_id = ? 
                ORDER BY p.published_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }
        
        return $this->db->fetchAll($sql, [$categoryId]);
    }

    /**
     * Get posts by author
     */
    public function getByAuthor($authorId, $limit = null)
    {
        $sql = "SELECT p.*, u.full_name as author_name, c.name as category_name 
                FROM {$this->table} p 
                LEFT JOIN users u ON p.author_id = u.id 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.is_published = 1 AND p.author_id = ? 
                ORDER BY p.published_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }
        
        return $this->db->fetchAll($sql, [$authorId]);
    }

    /**
     * Create post with slug generation
     */
    public function createPost($data)
    {
        if (!isset($data['slug']) || empty($data['slug'])) {
            $data['slug'] = $this->generateSlug($data['title']);
        }
        
        if (!isset($data['published_at']) && isset($data['is_published']) && $data['is_published']) {
            $data['published_at'] = date('Y-m-d H:i:s');
        }
        
        return $this->create($data);
    }

    /**
     * Update post with slug generation
     */
    public function updatePost($id, $data)
    {
        if (isset($data['title']) && (!isset($data['slug']) || empty($data['slug']))) {
            $data['slug'] = $this->generateSlug($data['title'], $id);
        }
        
        if (!isset($data['published_at']) && isset($data['is_published']) && $data['is_published']) {
            $currentPost = $this->find($id);
            if (!$currentPost['published_at']) {
                $data['published_at'] = date('Y-m-d H:i:s');
            }
        }
        
        return $this->update($id, $data);
    }

    /**
     * Get post tags
     */
    public function getTags($postId)
    {
        $sql = "SELECT t.* FROM tags t 
                INNER JOIN post_tags pt ON t.id = pt.tag_id 
                WHERE pt.post_id = ? 
                ORDER BY t.name ASC";
        return $this->db->fetchAll($sql, [$postId]);
    }

    /**
     * Add tag to post
     */
    public function addTag($postId, $tagId)
    {
        $sql = "INSERT IGNORE INTO post_tags (post_id, tag_id) VALUES (?, ?)";
        return $this->db->query($sql, [$postId, $tagId]);
    }

    /**
     * Remove tag from post
     */
    public function removeTag($postId, $tagId)
    {
        $sql = "DELETE FROM post_tags WHERE post_id = ? AND tag_id = ?";
        return $this->db->query($sql, [$postId, $tagId]);
    }

    /**
     * Set post tags
     */
    public function setTags($postId, $tagIds)
    {
        // Remove existing tags
        $this->db->query("DELETE FROM post_tags WHERE post_id = ?", [$postId]);
        
        // Add new tags
        if (!empty($tagIds)) {
            $values = [];
            $params = [];
            foreach ($tagIds as $tagId) {
                $values[] = "(?, ?)";
                $params[] = $postId;
                $params[] = $tagId;
            }
            
            $sql = "INSERT INTO post_tags (post_id, tag_id) VALUES " . implode(', ', $values);
            return $this->db->query($sql, $params);
        }
        
        return true;
    }

    /**
     * Get related posts
     */
    public function getRelated($postId, $limit = 4)
    {
        $sql = "SELECT p.*, u.full_name as author_name, c.name as category_name 
                FROM {$this->table} p 
                LEFT JOIN users u ON p.author_id = u.id 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.is_published = 1 AND p.id != ? 
                ORDER BY RAND() 
                LIMIT {$limit}";
        return $this->db->fetchAll($sql, [$postId]);
    }

    /**
     * Get post comments
     */
    public function getComments($postId, $approvedOnly = true)
    {
        $sql = "SELECT * FROM comments WHERE post_id = ?";
        $params = [$postId];
        
        if ($approvedOnly) {
            $sql .= " AND is_approved = 1";
        }
        
        $sql .= " ORDER BY created_at ASC";
        
        return $this->db->fetchAll($sql, $params);
    }

    /**
     * Add comment to post
     */
    public function addComment($postId, $data)
    {
        $data['post_id'] = $postId;
        $data['ip_address'] = $_SERVER['REMOTE_ADDR'] ?? '';
        $data['user_agent'] = $_SERVER['HTTP_USER_AGENT'] ?? '';
        
        $sql = "INSERT INTO comments (post_id, author_name, author_email, content, ip_address, user_agent) 
                VALUES (?, ?, ?, ?, ?, ?)";
        
        return $this->db->query($sql, [
            $data['post_id'],
            $data['author_name'],
            $data['author_email'],
            $data['content'],
            $data['ip_address'],
            $data['user_agent']
        ]);
    }
}