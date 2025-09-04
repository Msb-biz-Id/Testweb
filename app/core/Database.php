<?php

namespace App\Core;

use PDO;
use PDOException;

/**
 * Database Connection Class
 * Handles database connection using PDO
 */
class Database
{
    private static $instance = null;
    private $connection;
    private $config;

    private function __construct()
    {
        $this->config = require __DIR__ . '/../../config/database.php';
        $this->connect();
    }

    /**
     * Get database instance (Singleton pattern)
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Establish database connection
     */
    private function connect()
    {
        try {
            $dsn = "mysql:host={$this->config['host']};dbname={$this->config['dbname']};charset={$this->config['charset']}";
            
            $this->connection = new PDO(
                $dsn,
                $this->config['username'],
                $this->config['password'],
                $this->config['options']
            );
        } catch (PDOException $e) {
            throw new PDOException("Database connection failed: " . $e->getMessage());
        }
    }

    /**
     * Get PDO connection
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * Execute a prepared statement
     */
    public function query($sql, $params = [])
    {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            throw new PDOException("Query execution failed: " . $e->getMessage());
        }
    }

    /**
     * Fetch all rows
     */
    public function fetchAll($sql, $params = [])
    {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }

    /**
     * Fetch single row
     */
    public function fetch($sql, $params = [])
    {
        $stmt = $this->query($sql, $params);
        return $stmt->fetch();
    }

    /**
     * Get last inserted ID
     */
    public function lastInsertId()
    {
        return $this->connection->lastInsertId();
    }

    /**
     * Begin transaction
     */
    public function beginTransaction()
    {
        return $this->connection->beginTransaction();
    }

    /**
     * Commit transaction
     */
    public function commit()
    {
        return $this->connection->commit();
    }

    /**
     * Rollback transaction
     */
    public function rollback()
    {
        return $this->connection->rollback();
    }

    /**
     * Check if in transaction
     */
    public function inTransaction()
    {
        return $this->connection->inTransaction();
    }

    /**
     * Prevent cloning
     */
    private function __clone() {}

    /**
     * Prevent unserialization
     */
    public function __wakeup()
    {
        throw new Exception("Cannot unserialize singleton");
    }
}