<?php

namespace App\Models;

/**
 * Product Model
 * Handles product data and operations
 */
class Product extends Model
{
    protected $table = 'products';
    protected $fillable = [
        'name', 'slug', 'description', 'short_description', 'price', 'sale_price',
        'sku', 'stock_quantity', 'is_featured', 'is_published', 'marketplace_url',
        'meta_title', 'meta_description'
    ];

    /**
     * Get published products
     */
    public function getPublished()
    {
        return $this->where('is_published', 1);
    }

    /**
     * Get featured products
     */
    public function getFeatured($limit = null)
    {
        $sql = "SELECT * FROM {$this->table} WHERE is_featured = 1 AND is_published = 1 ORDER BY created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }
        
        return $this->db->fetchAll($sql);
    }

    /**
     * Get product by slug
     */
    public function findBySlug($slug)
    {
        return $this->whereFirst('slug', $slug);
    }

    /**
     * Search products
     */
    public function search($query, $limit = null)
    {
        $sql = "SELECT * FROM {$this->table} WHERE is_published = 1 AND (
            name LIKE ? OR 
            description LIKE ? OR 
            short_description LIKE ? OR
            sku LIKE ?
        ) ORDER BY name ASC";
        
        $params = ["%{$query}%", "%{$query}%", "%{$query}%", "%{$query}%"];
        
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }
        
        return $this->db->fetchAll($sql, $params);
    }

    /**
     * Get products with pagination
     */
    public function getPaginated($page = 1, $perPage = 12, $filters = [])
    {
        $sql = "SELECT * FROM {$this->table} WHERE is_published = 1";
        $params = [];
        
        // Apply filters
        if (isset($filters['featured']) && $filters['featured']) {
            $sql .= " AND is_featured = 1";
        }
        
        if (isset($filters['search']) && !empty($filters['search'])) {
            $sql .= " AND (name LIKE ? OR description LIKE ?)";
            $searchTerm = "%{$filters['search']}%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        $sql .= " ORDER BY created_at DESC";
        
        $offset = ($page - 1) * $perPage;
        $sql .= " LIMIT {$perPage} OFFSET {$offset}";
        
        $data = $this->db->fetchAll($sql, $params);
        
        // Get total count
        $countSql = "SELECT COUNT(*) as total FROM {$this->table} WHERE is_published = 1";
        $countParams = [];
        
        if (isset($filters['featured']) && $filters['featured']) {
            $countSql .= " AND is_featured = 1";
        }
        
        if (isset($filters['search']) && !empty($filters['search'])) {
            $countSql .= " AND (name LIKE ? OR description LIKE ?)";
            $searchTerm = "%{$filters['search']}%";
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
     * Create product with slug generation
     */
    public function createProduct($data)
    {
        if (!isset($data['slug']) || empty($data['slug'])) {
            $data['slug'] = $this->generateSlug($data['name']);
        }
        
        return $this->create($data);
    }

    /**
     * Update product with slug generation
     */
    public function updateProduct($id, $data)
    {
        if (isset($data['name']) && (!isset($data['slug']) || empty($data['slug']))) {
            $data['slug'] = $this->generateSlug($data['name'], $id);
        }
        
        return $this->update($id, $data);
    }

    /**
     * Get product images
     */
    public function getImages($productId)
    {
        $sql = "SELECT * FROM product_images WHERE product_id = ? ORDER BY sort_order ASC, is_primary DESC";
        return $this->db->fetchAll($sql, [$productId]);
    }

    /**
     * Add product image
     */
    public function addImage($productId, $imagePath, $altText = '', $isPrimary = false, $sortOrder = 0)
    {
        $sql = "INSERT INTO product_images (product_id, image_path, alt_text, is_primary, sort_order) VALUES (?, ?, ?, ?, ?)";
        return $this->db->query($sql, [$productId, $imagePath, $altText, $isPrimary, $sortOrder]);
    }

    /**
     * Remove product image
     */
    public function removeImage($imageId)
    {
        $sql = "DELETE FROM product_images WHERE id = ?";
        return $this->db->query($sql, [$imageId]);
    }

    /**
     * Set primary image
     */
    public function setPrimaryImage($productId, $imageId)
    {
        // Remove primary from all images
        $this->db->query("UPDATE product_images SET is_primary = 0 WHERE product_id = ?", [$productId]);
        
        // Set new primary
        $sql = "UPDATE product_images SET is_primary = 1 WHERE id = ? AND product_id = ?";
        return $this->db->query($sql, [$imageId, $productId]);
    }

    /**
     * Get related products
     */
    public function getRelated($productId, $limit = 4)
    {
        $sql = "SELECT * FROM {$this->table} WHERE is_published = 1 AND id != ? ORDER BY RAND() LIMIT {$limit}";
        return $this->db->fetchAll($sql, [$productId]);
    }

    /**
     * Update stock quantity
     */
    public function updateStock($id, $quantity)
    {
        return $this->update($id, ['stock_quantity' => $quantity]);
    }

    /**
     * Check if product is in stock
     */
    public function isInStock($id)
    {
        $product = $this->find($id);
        return $product && $product['stock_quantity'] > 0;
    }
}