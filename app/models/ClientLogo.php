<?php

namespace App\Models;

/**
 * Client Logo Model
 * Handles client logos for carousel
 */
class ClientLogo extends Model
{
    protected $table = 'client_logos';
    protected $fillable = [
        'company_name', 'logo_path', 'website_url', 'alt_text',
        'is_published', 'sort_order'
    ];

    /**
     * Get published logos
     */
    public function getPublished()
    {
        return $this->where('is_published', 1);
    }

    /**
     * Get logos ordered by sort order
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