<?php

namespace App\Models;

/**
 * User Model
 * Handles user authentication and management
 */
class User extends Model
{
    protected $table = 'users';
    protected $fillable = [
        'username', 'email', 'password', 'full_name', 'role', 'is_active'
    ];
    protected $hidden = ['password'];

    /**
     * Authenticate user
     */
    public function authenticate($username, $password)
    {
        $user = $this->whereFirst('username', $username);
        
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        
        return false;
    }

    /**
     * Create user with hashed password
     */
    public function createUser($data)
    {
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        return $this->create($data);
    }

    /**
     * Update user password
     */
    public function updatePassword($id, $password)
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        return $this->update($id, ['password' => $hashedPassword]);
    }

    /**
     * Get user by email
     */
    public function findByEmail($email)
    {
        return $this->whereFirst('email', $email);
    }

    /**
     * Get user by username
     */
    public function findByUsername($username)
    {
        return $this->whereFirst('username', $username);
    }

    /**
     * Check if email exists
     */
    public function emailExists($email, $excludeId = null)
    {
        $sql = "SELECT id FROM {$this->table} WHERE email = ?";
        $params = [$email];
        
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        return $this->db->fetch($sql, $params) !== false;
    }

    /**
     * Check if username exists
     */
    public function usernameExists($username, $excludeId = null)
    {
        $sql = "SELECT id FROM {$this->table} WHERE username = ?";
        $params = [$username];
        
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        return $this->db->fetch($sql, $params) !== false;
    }

    /**
     * Get active users
     */
    public function getActiveUsers()
    {
        return $this->where('is_active', 1);
    }

    /**
     * Activate user
     */
    public function activate($id)
    {
        return $this->update($id, ['is_active' => 1]);
    }

    /**
     * Deactivate user
     */
    public function deactivate($id)
    {
        return $this->update($id, ['is_active' => 0]);
    }
}