<?php

namespace App\Models;

/**
 * Configuration Model
 * Handles global website configurations
 */
class Configuration extends Model
{
    protected $table = 'configurations';
    protected $fillable = ['config_key', 'config_value', 'config_type', 'description'];

    /**
     * Get configuration value
     */
    public function get($key, $default = null)
    {
        $config = $this->whereFirst('config_key', $key);
        
        if (!$config) {
            return $default;
        }
        
        $value = $config['config_value'];
        
        // Convert value based on type
        switch ($config['config_type']) {
            case 'boolean':
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);
            case 'integer':
                return (int) $value;
            case 'json':
                return json_decode($value, true);
            default:
                return $value;
        }
    }

    /**
     * Set configuration value
     */
    public function set($key, $value, $type = 'string', $description = null)
    {
        // Convert value to string for storage
        switch ($type) {
            case 'boolean':
                $value = $value ? 'true' : 'false';
                break;
            case 'integer':
                $value = (string) $value;
                break;
            case 'json':
                $value = json_encode($value);
                break;
            default:
                $value = (string) $value;
        }
        
        // Check if config exists
        $existing = $this->whereFirst('config_key', $key);
        
        if ($existing) {
            return $this->update($existing['id'], [
                'config_value' => $value,
                'config_type' => $type,
                'description' => $description
            ]);
        } else {
            return $this->create([
                'config_key' => $key,
                'config_value' => $value,
                'config_type' => $type,
                'description' => $description
            ]);
        }
    }

    /**
     * Get all configurations as array
     */
    public function getAll()
    {
        $configs = $this->all();
        $result = [];
        
        foreach ($configs as $config) {
            $key = $config['config_key'];
            $value = $config['config_value'];
            
            // Convert value based on type
            switch ($config['config_type']) {
                case 'boolean':
                    $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);
                    break;
                case 'integer':
                    $value = (int) $value;
                    break;
                case 'json':
                    $value = json_decode($value, true);
                    break;
            }
            
            $result[$key] = $value;
        }
        
        return $result;
    }

    /**
     * Get configurations by group
     */
    public function getByGroup($group)
    {
        $sql = "SELECT * FROM {$this->table} WHERE config_key LIKE ? ORDER BY config_key ASC";
        $configs = $this->db->fetchAll($sql, ["{$group}_%"]);
        
        $result = [];
        foreach ($configs as $config) {
            $key = str_replace("{$group}_", '', $config['config_key']);
            $value = $config['config_value'];
            
            // Convert value based on type
            switch ($config['config_type']) {
                case 'boolean':
                    $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);
                    break;
                case 'integer':
                    $value = (int) $value;
                    break;
                case 'json':
                    $value = json_decode($value, true);
                    break;
            }
            
            $result[$key] = $value;
        }
        
        return $result;
    }

    /**
     * Bulk update configurations
     */
    public function updateBulk($configs)
    {
        $this->db->beginTransaction();
        
        try {
            foreach ($configs as $key => $value) {
                $this->set($key, $value);
            }
            
            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollback();
            return false;
        }
    }

    /**
     * Delete configuration
     */
    public function deleteConfig($key)
    {
        $config = $this->whereFirst('config_key', $key);
        if ($config) {
            return $this->delete($config['id']);
        }
        return false;
    }
}