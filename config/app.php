<?php
/**
 * Application Configuration
 * Main configuration file for the application
 */

return [
    'name' => 'Testweb Jersey',
    'version' => '1.0.0',
    'debug' => $_ENV['APP_DEBUG'] ?? true,
    'url' => $_ENV['APP_URL'] ?? 'http://localhost',
    'timezone' => 'Asia/Jakarta',
    
    // Paths
    'paths' => [
        'root' => dirname(__DIR__),
        'app' => dirname(__DIR__) . '/app',
        'public' => dirname(__DIR__) . '/public',
        'storage' => dirname(__DIR__) . '/storage',
        'uploads' => dirname(__DIR__) . '/public/uploads',
    ],
    
    // Security
    'security' => [
        'session_name' => 'testweb_session',
        'session_lifetime' => 7200, // 2 hours
        'csrf_token_name' => '_token',
        'password_min_length' => 8,
    ],
    
    // Upload settings
    'upload' => [
        'max_size' => 5 * 1024 * 1024, // 5MB
        'allowed_types' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
        'image_quality' => 85,
    ],
    
    // Pagination
    'pagination' => [
        'per_page' => 12,
        'max_per_page' => 50,
    ],
    
    // Cache
    'cache' => [
        'enabled' => true,
        'driver' => 'file',
        'ttl' => 3600, // 1 hour
    ]
];