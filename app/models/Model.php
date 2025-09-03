<?php

namespace App\Models;

use App\Core\Database;

/**
 * Base Model Class
 * Provides common database operations for all models
 */
abstract class Model
{
    protected $db;
    protected $table;
    protected $primaryKey = 'id';
    protected $fillable = [];
    protected $hidden = [];
    protected $timestamps = true;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Find record by ID
     */
    public function find($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?";
        return $this->db->fetch($sql, [$id]);
    }

    /**
     * Find all records
     */
    public function all()
    {
        $sql = "SELECT * FROM {$this->table}";
        return $this->db->fetchAll($sql);
    }

    /**
     * Find records with conditions
     */
    public function where($column, $operator, $value = null)
    {
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }

        $sql = "SELECT * FROM {$this->table} WHERE {$column} {$operator} ?";
        return $this->db->fetchAll($sql, [$value]);
    }

    /**
     * Find single record with conditions
     */
    public function whereFirst($column, $operator, $value = null)
    {
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }

        $sql = "SELECT * FROM {$this->table} WHERE {$column} {$operator} ? LIMIT 1";
        return $this->db->fetch($sql, [$value]);
    }

    /**
     * Create new record
     */
    public function create($data)
    {
        // Filter fillable fields
        $data = $this->filterFillable($data);
        
        // Add timestamps
        if ($this->timestamps) {
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['updated_at'] = date('Y-m-d H:i:s');
        }

        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        
        $this->db->query($sql, $data);
        return $this->db->lastInsertId();
    }

    /**
     * Update record
     */
    public function update($id, $data)
    {
        // Filter fillable fields
        $data = $this->filterFillable($data);
        
        // Add updated timestamp
        if ($this->timestamps) {
            $data['updated_at'] = date('Y-m-d H:i:s');
        }

        $setClause = [];
        foreach (array_keys($data) as $column) {
            $setClause[] = "{$column} = :{$column}";
        }
        $setClause = implode(', ', $setClause);
        
        $sql = "UPDATE {$this->table} SET {$setClause} WHERE {$this->primaryKey} = :id";
        $data['id'] = $id;
        
        return $this->db->query($sql, $data);
    }

    /**
     * Delete record
     */
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?";
        return $this->db->query($sql, [$id]);
    }

    /**
     * Count records
     */
    public function count($conditions = [])
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table}";
        $params = [];
        
        if (!empty($conditions)) {
            $whereClause = [];
            foreach ($conditions as $column => $value) {
                $whereClause[] = "{$column} = ?";
                $params[] = $value;
            }
            $sql .= " WHERE " . implode(' AND ', $whereClause);
        }
        
        $result = $this->db->fetch($sql, $params);
        return $result['count'];
    }

    /**
     * Paginate results
     */
    public function paginate($page = 1, $perPage = 15, $conditions = [])
    {
        $offset = ($page - 1) * $perPage;
        
        $sql = "SELECT * FROM {$this->table}";
        $params = [];
        
        if (!empty($conditions)) {
            $whereClause = [];
            foreach ($conditions as $column => $value) {
                $whereClause[] = "{$column} = ?";
                $params[] = $value;
            }
            $sql .= " WHERE " . implode(' AND ', $whereClause);
        }
        
        $sql .= " LIMIT {$perPage} OFFSET {$offset}";
        $data = $this->db->fetchAll($sql, $params);
        
        $total = $this->count($conditions);
        
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
     * Execute custom query
     */
    public function query($sql, $params = [])
    {
        return $this->db->query($sql, $params);
    }

    /**
     * Execute custom query and fetch all
     */
    public function fetchAll($sql, $params = [])
    {
        return $this->db->fetchAll($sql, $params);
    }

    /**
     * Execute custom query and fetch single
     */
    public function fetch($sql, $params = [])
    {
        return $this->db->fetch($sql, $params);
    }

    /**
     * Filter fillable fields
     */
    protected function filterFillable($data)
    {
        if (empty($this->fillable)) {
            return $data;
        }
        
        return array_intersect_key($data, array_flip($this->fillable));
    }

    /**
     * Hide sensitive fields
     */
    protected function hideFields($data)
    {
        if (empty($this->hidden)) {
            return $data;
        }
        
        return array_diff_key($data, array_flip($this->hidden));
    }

    /**
     * Generate slug
     */
    protected function generateSlug($string, $id = null)
    {
        $slug = strtolower($string);
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        $slug = trim($slug, '-');
        
        // Check if slug exists
        $sql = "SELECT {$this->primaryKey} FROM {$this->table} WHERE slug = ?";
        $params = [$slug];
        
        if ($id) {
            $sql .= " AND {$this->primaryKey} != ?";
            $params[] = $id;
        }
        
        $existing = $this->db->fetch($sql, $params);
        
        if ($existing) {
            $counter = 1;
            $originalSlug = $slug;
            
            do {
                $slug = $originalSlug . '-' . $counter;
                $params[0] = $slug;
                $existing = $this->db->fetch($sql, $params);
                $counter++;
            } while ($existing);
        }
        
        return $slug;
    }
}