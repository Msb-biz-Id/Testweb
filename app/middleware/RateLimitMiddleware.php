<?php

namespace App\Middleware;

use App\Core\Security;

/**
 * Rate Limiting Middleware
 * Prevents abuse and brute force attacks
 */
class RateLimitMiddleware
{
    private $maxAttempts;
    private $timeWindow;

    public function __construct($maxAttempts = 10, $timeWindow = 300)
    {
        $this->maxAttempts = $maxAttempts;
        $this->timeWindow = $timeWindow;
    }

    public function handle()
    {
        $key = 'general_' . md5($_SERVER['REQUEST_URI']);
        
        if (!Security::checkRateLimit($key, $this->maxAttempts, $this->timeWindow)) {
            Security::logSecurityEvent('rate_limit_exceeded', [
                'url' => $_SERVER['REQUEST_URI'],
                'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
            ]);
            
            http_response_code(429);
            header('Retry-After: ' . $this->timeWindow);
            
            if ($this->isAjaxRequest()) {
                header('Content-Type: application/json');
                echo json_encode([
                    'error' => 'Too many requests',
                    'retry_after' => $this->timeWindow
                ]);
            } else {
                echo '<h1>429 - Too Many Requests</h1>';
                echo '<p>Please try again later.</p>';
            }
            
            exit;
        }
        
        return true;
    }

    private function isAjaxRequest()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
}