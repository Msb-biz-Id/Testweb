<?php

namespace App\Core;

/**
 * Logger Class
 * Handles application logging
 */
class Logger
{
    private $logDir;
    private $logLevel;

    const EMERGENCY = 0;
    const ALERT = 1;
    const CRITICAL = 2;
    const ERROR = 3;
    const WARNING = 4;
    const NOTICE = 5;
    const INFO = 6;
    const DEBUG = 7;

    public function __construct($logDir = null, $logLevel = self::INFO)
    {
        $this->logDir = $logDir ?: __DIR__ . '/../../storage/logs';
        $this->logLevel = $logLevel;
        
        if (!is_dir($this->logDir)) {
            mkdir($this->logDir, 0755, true);
        }
    }

    /**
     * Log emergency message
     */
    public function emergency($message, $context = [])
    {
        $this->log(self::EMERGENCY, $message, $context);
    }

    /**
     * Log alert message
     */
    public function alert($message, $context = [])
    {
        $this->log(self::ALERT, $message, $context);
    }

    /**
     * Log critical message
     */
    public function critical($message, $context = [])
    {
        $this->log(self::CRITICAL, $message, $context);
    }

    /**
     * Log error message
     */
    public function error($message, $context = [])
    {
        $this->log(self::ERROR, $message, $context);
    }

    /**
     * Log warning message
     */
    public function warning($message, $context = [])
    {
        $this->log(self::WARNING, $message, $context);
    }

    /**
     * Log notice message
     */
    public function notice($message, $context = [])
    {
        $this->log(self::NOTICE, $message, $context);
    }

    /**
     * Log info message
     */
    public function info($message, $context = [])
    {
        $this->log(self::INFO, $message, $context);
    }

    /**
     * Log debug message
     */
    public function debug($message, $context = [])
    {
        $this->log(self::DEBUG, $message, $context);
    }

    /**
     * Log message
     */
    public function log($level, $message, $context = [])
    {
        if ($level > $this->logLevel) {
            return;
        }

        $levelName = $this->getLevelName($level);
        $timestamp = date('Y-m-d H:i:s');
        $contextStr = !empty($context) ? ' ' . json_encode($context) : '';
        
        $logMessage = "[{$timestamp}] {$levelName}: {$message}{$contextStr}" . PHP_EOL;
        
        $logFile = $this->logDir . '/' . date('Y-m-d') . '.log';
        
        file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);
    }

    /**
     * Get level name
     */
    private function getLevelName($level)
    {
        $levels = [
            self::EMERGENCY => 'EMERGENCY',
            self::ALERT => 'ALERT',
            self::CRITICAL => 'CRITICAL',
            self::ERROR => 'ERROR',
            self::WARNING => 'WARNING',
            self::NOTICE => 'NOTICE',
            self::INFO => 'INFO',
            self::DEBUG => 'DEBUG'
        ];
        
        return $levels[$level] ?? 'UNKNOWN';
    }

    /**
     * Log database query
     */
    public function query($sql, $params = [], $executionTime = null)
    {
        $context = [
            'sql' => $sql,
            'params' => $params,
            'execution_time' => $executionTime
        ];
        
        $this->debug('Database Query', $context);
    }

    /**
     * Log user action
     */
    public function userAction($action, $userId = null, $details = [])
    {
        $context = [
            'action' => $action,
            'user_id' => $userId,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
            'details' => $details
        ];
        
        $this->info('User Action', $context);
    }

    /**
     * Log performance metrics
     */
    public function performance($metric, $value, $unit = 'ms')
    {
        $context = [
            'metric' => $metric,
            'value' => $value,
            'unit' => $unit
        ];
        
        $this->info('Performance', $context);
    }
}