<?php

namespace App\Core;

/**
 * Base Controller Class
 * Provides common functionality for all controllers
 */
abstract class Controller
{
    protected $request;
    protected $db;
    protected $config;

    public function __construct()
    {
        $this->request = new Request();
        $this->db = Database::getInstance();
        $this->config = require __DIR__ . '/../../config/app.php';
        
        // Start session if not started
        if (session_status() === PHP_SESSION_NONE) {
            // Secure session configuration
            ini_set('session.cookie_httponly', 1);
            ini_set('session.cookie_secure', isset($_SERVER['HTTPS']));
            ini_set('session.use_strict_mode', 1);
            ini_set('session.cookie_samesite', 'Strict');
            session_start();
        }
        
        // Set security headers
        Security::setSecurityHeaders();
        
        // Sanitize all input
        $this->request->sanitizeAll();
        
        // Check for suspicious activity
        if (Security::detectSuspiciousActivity()) {
            Security::logSecurityEvent('suspicious_activity_detected', [
                'url' => $_SERVER['REQUEST_URI'],
                'method' => $_SERVER['REQUEST_METHOD']
            ]);
        }
    }

    /**
     * Render a view
     */
    protected function view($view, $data = [])
    {
        $viewPath = __DIR__ . '/../views/' . $view . '.php';
        
        if (!file_exists($viewPath)) {
            throw new \Exception("View not found: {$view}");
        }

        // Extract data to variables
        extract($data);
        
        // Start output buffering
        ob_start();
        include $viewPath;
        $content = ob_get_clean();
        
        return $content;
    }

    /**
     * Render view and output
     */
    protected function render($view, $data = [])
    {
        echo $this->view($view, $data);
    }

    /**
     * Return JSON response
     */
    protected function json($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    /**
     * Redirect to URL
     */
    protected function redirect($url, $statusCode = 302)
    {
        header("Location: {$url}", true, $statusCode);
        exit;
    }

    /**
     * Redirect back with message
     */
    protected function redirectBack($message = null, $type = 'info')
    {
        if ($message) {
            $this->setFlashMessage($message, $type);
        }
        
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        $this->redirect($referer);
    }

    /**
     * Set flash message
     */
    protected function setFlashMessage($message, $type = 'info')
    {
        $_SESSION['flash_messages'][] = [
            'message' => $message,
            'type' => $type
        ];
    }

    /**
     * Get flash messages
     */
    protected function getFlashMessages()
    {
        $messages = $_SESSION['flash_messages'] ?? [];
        unset($_SESSION['flash_messages']);
        return $messages;
    }

    /**
     * Check if user is authenticated
     */
    protected function isAuthenticated()
    {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }

    /**
     * Get current user
     */
    protected function getCurrentUser()
    {
        if (!$this->isAuthenticated()) {
            return null;
        }

        $user = $this->db->fetch(
            "SELECT * FROM users WHERE id = ? AND is_active = 1",
            [$_SESSION['user_id']]
        );

        return $user;
    }

    /**
     * Require authentication
     */
    protected function requireAuth()
    {
        if (!$this->isAuthenticated()) {
            $this->redirect('/admin/login');
        }
    }

    /**
     * Validate request data
     */
    protected function validate($rules)
    {
        $errors = $this->request->validate($rules);
        
        if (!empty($errors)) {
            $this->json([
                'success' => false,
                'errors' => $errors
            ], 422);
        }
        
        return true;
    }

    /**
     * Upload file
     */
    protected function uploadFile($file, $directory = 'uploads')
    {
        if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
            return false;
        }

        // Validate file type
        $allowedTypes = $this->config['upload']['allowed_types'];
        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        
        if (!in_array($fileExtension, $allowedTypes)) {
            return false;
        }

        // Validate file size
        $maxSize = $this->config['upload']['max_size'];
        if ($file['size'] > $maxSize) {
            return false;
        }

        // Generate unique filename
        $filename = uniqid() . '_' . time() . '.' . $fileExtension;
        $uploadPath = $this->config['paths']['public'] . '/' . $directory;
        
        // Create directory if not exists
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $filePath = $uploadPath . '/' . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            return $directory . '/' . $filename;
        }

        return false;
    }

    /**
     * Generate slug from string
     */
    protected function generateSlug($string)
    {
        // Convert to lowercase
        $string = strtolower($string);
        
        // Replace spaces and special characters with hyphens
        $string = preg_replace('/[^a-z0-9]+/', '-', $string);
        
        // Remove leading/trailing hyphens
        $string = trim($string, '-');
        
        return $string;
    }

    /**
     * Paginate results
     */
    protected function paginate($query, $params = [], $page = 1, $perPage = null)
    {
        $perPage = $perPage ?: $this->config['pagination']['per_page'];
        $offset = ($page - 1) * $perPage;
        
        // Get total count
        $countQuery = "SELECT COUNT(*) as total FROM ({$query}) as count_table";
        $total = $this->db->fetch($countQuery, $params)['total'];
        
        // Get paginated results
        $paginatedQuery = $query . " LIMIT {$perPage} OFFSET {$offset}";
        $results = $this->db->fetchAll($paginatedQuery, $params);
        
        return [
            'data' => $results,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => ceil($total / $perPage),
            'from' => $offset + 1,
            'to' => min($offset + $perPage, $total)
        ];
    }

    /**
     * Get configuration value
     */
    protected function config($key, $default = null)
    {
        $keys = explode('.', $key);
        $value = $this->config;
        
        foreach ($keys as $k) {
            if (isset($value[$k])) {
                $value = $value[$k];
            } else {
                return $default;
            }
        }
        
        return $value;
    }
}