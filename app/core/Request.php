<?php

namespace App\Core;

/**
 * Request Class
 * Handles HTTP request data and validation
 */
class Request
{
    private $data = [];
    private $files = [];
    private $headers = [];

    public function __construct()
    {
        $this->data = array_merge($_GET, $_POST);
        $this->files = $_FILES;
        $this->headers = $this->getAllHeaders();
    }

    /**
     * Get all headers
     */
    private function getAllHeaders()
    {
        if (function_exists('getallheaders')) {
            return getallheaders();
        }

        $headers = [];
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }

    /**
     * Get request method
     */
    public function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Check if request is GET
     */
    public function isGet()
    {
        return $this->method() === 'GET';
    }

    /**
     * Check if request is POST
     */
    public function isPost()
    {
        return $this->method() === 'POST';
    }

    /**
     * Check if request is PUT
     */
    public function isPut()
    {
        return $this->method() === 'PUT';
    }

    /**
     * Check if request is DELETE
     */
    public function isDelete()
    {
        return $this->method() === 'DELETE';
    }

    /**
     * Check if request is AJAX
     */
    public function isAjax()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    /**
     * Get input data
     */
    public function input($key = null, $default = null)
    {
        if ($key === null) {
            return $this->data;
        }

        return $this->data[$key] ?? $default;
    }

    /**
     * Get GET parameter
     */
    public function get($key = null, $default = null)
    {
        if ($key === null) {
            return $_GET;
        }

        return $_GET[$key] ?? $default;
    }

    /**
     * Get POST parameter
     */
    public function post($key = null, $default = null)
    {
        if ($key === null) {
            return $_POST;
        }

        return $_POST[$key] ?? $default;
    }

    /**
     * Get file upload
     */
    public function file($key)
    {
        return $this->files[$key] ?? null;
    }

    /**
     * Get all files
     */
    public function files()
    {
        return $this->files;
    }

    /**
     * Get header
     */
    public function header($key, $default = null)
    {
        return $this->headers[$key] ?? $default;
    }

    /**
     * Get all headers
     */
    public function headers()
    {
        return $this->headers;
    }

    /**
     * Get request URI
     */
    public function uri()
    {
        return $_SERVER['REQUEST_URI'];
    }

    /**
     * Get request path
     */
    public function path()
    {
        $uri = $this->uri();
        if (($pos = strpos($uri, '?')) !== false) {
            $uri = substr($uri, 0, $pos);
        }
        return $uri;
    }

    /**
     * Get query string
     */
    public function query()
    {
        return $_SERVER['QUERY_STRING'] ?? '';
    }

    /**
     * Get user IP address
     */
    public function ip()
    {
        $ipKeys = ['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'];
        
        foreach ($ipKeys as $key) {
            if (!empty($_SERVER[$key])) {
                $ip = $_SERVER[$key];
                if (strpos($ip, ',') !== false) {
                    $ip = trim(explode(',', $ip)[0]);
                }
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
            }
        }
        
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }

    /**
     * Get user agent
     */
    public function userAgent()
    {
        return $_SERVER['HTTP_USER_AGENT'] ?? '';
    }

    /**
     * Validate input data
     */
    public function validate($rules)
    {
        $errors = [];
        
        foreach ($rules as $field => $rule) {
            $value = $this->input($field);
            $fieldRules = is_array($rule) ? $rule : explode('|', $rule);
            
            foreach ($fieldRules as $singleRule) {
                $error = $this->validateRule($field, $value, $singleRule);
                if ($error) {
                    $errors[$field][] = $error;
                }
            }
        }
        
        return $errors;
    }

    /**
     * Validate single rule
     */
    private function validateRule($field, $value, $rule)
    {
        $params = explode(':', $rule);
        $ruleName = $params[0];
        $ruleValue = $params[1] ?? null;

        switch ($ruleName) {
            case 'required':
                if (empty($value)) {
                    return "The {$field} field is required.";
                }
                break;
                
            case 'email':
                if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    return "The {$field} field must be a valid email address.";
                }
                break;
                
            case 'min':
                if (!empty($value) && strlen($value) < $ruleValue) {
                    return "The {$field} field must be at least {$ruleValue} characters.";
                }
                break;
                
            case 'max':
                if (!empty($value) && strlen($value) > $ruleValue) {
                    return "The {$field} field must not exceed {$ruleValue} characters.";
                }
                break;
                
            case 'numeric':
                if (!empty($value) && !is_numeric($value)) {
                    return "The {$field} field must be numeric.";
                }
                break;
                
            case 'url':
                if (!empty($value) && !filter_var($value, FILTER_VALIDATE_URL)) {
                    return "The {$field} field must be a valid URL.";
                }
                break;
        }

        return null;
    }

    /**
     * Get CSRF token
     */
    public function csrfToken()
    {
        if (!isset($_SESSION['_token'])) {
            $_SESSION['_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['_token'];
    }

    /**
     * Verify CSRF token
     */
    public function verifyCsrfToken($token = null)
    {
        $token = $token ?: $this->input('_token');
        return isset($_SESSION['_token']) && hash_equals($_SESSION['_token'], $token);
    }
}