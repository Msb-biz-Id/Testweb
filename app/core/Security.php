<?php

namespace App\Core;

/**
 * Security Class
 * Handles security-related operations
 */
class Security
{
    /**
     * Generate CSRF token
     */
    public static function generateCsrfToken()
    {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Verify CSRF token
     */
    public static function verifyCsrfToken($token)
    {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * Sanitize input data
     */
    public static function sanitizeInput($data, $type = 'string')
    {
        if (is_array($data)) {
            return array_map(function($item) use ($type) {
                return self::sanitizeInput($item, $type);
            }, $data);
        }

        switch ($type) {
            case 'email':
                return filter_var(trim($data), FILTER_SANITIZE_EMAIL);
            case 'url':
                return filter_var(trim($data), FILTER_SANITIZE_URL);
            case 'int':
                return filter_var($data, FILTER_SANITIZE_NUMBER_INT);
            case 'float':
                return filter_var($data, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            case 'html':
                return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
            case 'string':
            default:
                return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
        }
    }

    /**
     * Validate email
     */
    public static function validateEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Validate URL
     */
    public static function validateUrl($url)
    {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * Generate secure random string
     */
    public static function generateRandomString($length = 32)
    {
        return bin2hex(random_bytes($length / 2));
    }

    /**
     * Hash password securely
     */
    public static function hashPassword($password)
    {
        return password_hash($password, PASSWORD_ARGON2ID, [
            'memory_cost' => 65536, // 64 MB
            'time_cost' => 4,       // 4 iterations
            'threads' => 3,         // 3 threads
        ]);
    }

    /**
     * Verify password
     */
    public static function verifyPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }

    /**
     * Rate limiting
     */
    public static function checkRateLimit($key, $maxAttempts = 5, $timeWindow = 300)
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $rateLimitKey = "rate_limit_{$key}_{$ip}";
        
        if (!isset($_SESSION[$rateLimitKey])) {
            $_SESSION[$rateLimitKey] = ['count' => 0, 'reset_time' => time() + $timeWindow];
        }
        
        $rateLimit = $_SESSION[$rateLimitKey];
        
        // Reset if time window expired
        if (time() > $rateLimit['reset_time']) {
            $_SESSION[$rateLimitKey] = ['count' => 0, 'reset_time' => time() + $timeWindow];
            $rateLimit = $_SESSION[$rateLimitKey];
        }
        
        if ($rateLimit['count'] >= $maxAttempts) {
            return false;
        }
        
        $_SESSION[$rateLimitKey]['count']++;
        return true;
    }

    /**
     * Log security events
     */
    public static function logSecurityEvent($event, $details = [])
    {
        $logData = [
            'timestamp' => date('Y-m-d H:i:s'),
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
            'event' => $event,
            'details' => $details
        ];
        
        $logFile = __DIR__ . '/../../storage/logs/security.log';
        $logDir = dirname($logFile);
        
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        file_put_contents($logFile, json_encode($logData) . "\n", FILE_APPEND | LOCK_EX);
    }

    /**
     * Check for suspicious activity
     */
    public static function detectSuspiciousActivity()
    {
        $suspiciousPatterns = [
            '/script/i',
            '/javascript/i',
            '/vbscript/i',
            '/onload/i',
            '/onerror/i',
            '/<iframe/i',
            '/<object/i',
            '/<embed/i',
            '/union.*select/i',
            '/drop.*table/i',
            '/delete.*from/i',
            '/insert.*into/i',
            '/update.*set/i'
        ];
        
        $inputs = array_merge($_GET, $_POST, $_COOKIE);
        
        foreach ($inputs as $key => $value) {
            if (is_string($value)) {
                foreach ($suspiciousPatterns as $pattern) {
                    if (preg_match($pattern, $value)) {
                        self::logSecurityEvent('suspicious_input', [
                            'key' => $key,
                            'value' => substr($value, 0, 100),
                            'pattern' => $pattern
                        ]);
                        return true;
                    }
                }
            }
        }
        
        return false;
    }

    /**
     * Set secure headers
     */
    public static function setSecurityHeaders()
    {
        // Prevent clickjacking
        header('X-Frame-Options: DENY');
        
        // Prevent MIME type sniffing
        header('X-Content-Type-Options: nosniff');
        
        // XSS Protection
        header('X-XSS-Protection: 1; mode=block');
        
        // Referrer Policy
        header('Referrer-Policy: strict-origin-when-cross-origin');
        
        // Content Security Policy
        $csp = "default-src 'self'; " .
               "script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://www.google.com https://www.gstatic.com; " .
               "style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; " .
               "img-src 'self' data: https:; " .
               "font-src 'self' https://cdnjs.cloudflare.com; " .
               "connect-src 'self' https://www.google.com; " .
               "frame-src https://www.google.com;";
        
        header("Content-Security-Policy: {$csp}");
        
        // Strict Transport Security (HTTPS only)
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
            header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
        }
        
        // Permissions Policy
        header('Permissions-Policy: geolocation=(), microphone=(), camera=(), payment=(), usb=(), magnetometer=(), gyroscope=(), speaker=()');
    }

    /**
     * Validate file upload
     */
    public static function validateFileUpload($file, $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'], $maxSize = 5242880)
    {
        $errors = [];
        
        // Check if file was uploaded
        if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'File upload failed';
            return $errors;
        }
        
        // Check file size
        if ($file['size'] > $maxSize) {
            $errors[] = 'File size exceeds maximum allowed size';
        }
        
        // Check file type
        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($fileExtension, $allowedTypes)) {
            $errors[] = 'File type not allowed';
        }
        
        // Check MIME type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        $allowedMimeTypes = [
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/webp'
        ];
        
        if (!in_array($mimeType, $allowedMimeTypes)) {
            $errors[] = 'Invalid file type';
        }
        
        // Check for malicious content
        $imageInfo = getimagesize($file['tmp_name']);
        if ($imageInfo === false) {
            $errors[] = 'Invalid image file';
        }
        
        return $errors;
    }

    /**
     * Generate secure filename
     */
    public static function generateSecureFilename($originalName)
    {
        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $filename = self::generateRandomString(32);
        return $filename . '.' . $extension;
    }
}