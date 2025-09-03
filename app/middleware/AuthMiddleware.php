<?php

namespace App\Middleware;

use App\Core\Security;

/**
 * Authentication Middleware
 * Protects admin routes
 */
class AuthMiddleware
{
    public function handle()
    {
        // Check if user is authenticated
        if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
            Security::logSecurityEvent('unauthorized_access_attempt', [
                'url' => $_SERVER['REQUEST_URI'],
                'method' => $_SERVER['REQUEST_METHOD']
            ]);
            
            header('Location: /admin/login');
            exit;
        }
        
        // Check session timeout (2 hours)
        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 7200)) {
            session_destroy();
            Security::logSecurityEvent('session_timeout', [
                'user_id' => $_SESSION['user_id'] ?? 'unknown'
            ]);
            
            header('Location: /admin/login?timeout=1');
            exit;
        }
        
        // Update last activity
        $_SESSION['last_activity'] = time();
        
        // Check for suspicious activity
        if (Security::detectSuspiciousActivity()) {
            Security::logSecurityEvent('suspicious_activity_detected', [
                'user_id' => $_SESSION['user_id'],
                'url' => $_SERVER['REQUEST_URI']
            ]);
            
            // Logout user and redirect
            session_destroy();
            header('Location: /admin/login?security=1');
            exit;
        }
        
        return true;
    }
}