<?php
/**
 * Main Entry Point
 * Bootstrap the application and handle all requests
 */

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set timezone
date_default_timezone_set('Asia/Jakarta');

// Define application paths
define('APP_ROOT', dirname(__DIR__));
define('APP_PATH', APP_ROOT . '/app');
define('PUBLIC_PATH', __DIR__);
define('CONFIG_PATH', APP_ROOT . '/config');

// Autoloader
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = APP_PATH . '/';
    
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    
    if (file_exists($file)) {
        require $file;
    }
});

// Load environment variables if .env file exists
if (file_exists(APP_ROOT . '/.env')) {
    $lines = file(APP_ROOT . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            list($key, $value) = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($value);
        }
    }
}

// Load configuration
$config = require CONFIG_PATH . '/app.php';

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_name($config['security']['session_name']);
    session_set_cookie_params($config['security']['session_lifetime']);
    session_start();
}

// Initialize router
use App\Core\Router;

$router = new Router();

// Frontend Routes
$router->get('/', 'HomeController@index');
$router->get('/produk', 'ProductController@index');
$router->get('/produk/{slug}', 'ProductController@show');
$router->get('/blog', 'BlogController@index');
$router->get('/blog/{slug}', 'BlogController@show');
$router->post('/blog/{slug}/comment', 'BlogController@addComment');
$router->get('/search', 'SearchController@index');
$router->get('/page/{slug}', 'PageController@show');

// Admin Routes
$router->get('/admin/login', 'Admin\AuthController@showLogin');
$router->post('/admin/login', 'Admin\AuthController@login');
$router->post('/admin/logout', 'Admin\AuthController@logout');

// Protected admin routes
$router->get('/admin', 'Admin\DashboardController@index', ['AuthMiddleware']);
$router->get('/admin/dashboard', 'Admin\DashboardController@index', ['AuthMiddleware']);

// Products management
$router->get('/admin/products', 'Admin\ProductController@index', ['AuthMiddleware']);
$router->get('/admin/products/create', 'Admin\ProductController@create', ['AuthMiddleware']);
$router->post('/admin/products', 'Admin\ProductController@store', ['AuthMiddleware']);
$router->get('/admin/products/{id}/edit', 'Admin\ProductController@edit', ['AuthMiddleware']);
$router->put('/admin/products/{id}', 'Admin\ProductController@update', ['AuthMiddleware']);
$router->delete('/admin/products/{id}', 'Admin\ProductController@delete', ['AuthMiddleware']);

// Posts management
$router->get('/admin/posts', 'Admin\PostController@index', ['AuthMiddleware']);
$router->get('/admin/posts/create', 'Admin\PostController@create', ['AuthMiddleware']);
$router->post('/admin/posts', 'Admin\PostController@store', ['AuthMiddleware']);
$router->get('/admin/posts/{id}/edit', 'Admin\PostController@edit', ['AuthMiddleware']);
$router->put('/admin/posts/{id}', 'Admin\PostController@update', ['AuthMiddleware']);
$router->delete('/admin/posts/{id}', 'Admin\PostController@delete', ['AuthMiddleware']);

// Pages management
$router->get('/admin/pages', 'Admin\PageController@index', ['AuthMiddleware']);
$router->get('/admin/pages/create', 'Admin\PageController@create', ['AuthMiddleware']);
$router->post('/admin/pages', 'Admin\PageController@store', ['AuthMiddleware']);
$router->get('/admin/pages/{id}/edit', 'Admin\PageController@edit', ['AuthMiddleware']);
$router->put('/admin/pages/{id}', 'Admin\PageController@update', ['AuthMiddleware']);
$router->delete('/admin/pages/{id}', 'Admin\PageController@delete', ['AuthMiddleware']);

// Settings
$router->get('/admin/settings', 'Admin\SettingsController@index', ['AuthMiddleware']);
$router->post('/admin/settings', 'Admin\SettingsController@update', ['AuthMiddleware']);

// Media management
$router->get('/admin/media', 'Admin\MediaController@index', ['AuthMiddleware']);
$router->post('/admin/media/upload', 'Admin\MediaController@upload', ['AuthMiddleware']);
$router->delete('/admin/media/{id}', 'Admin\MediaController@delete', ['AuthMiddleware']);

// Testimonials management
$router->get('/admin/testimonials', 'Admin\TestimonialController@index', ['AuthMiddleware']);
$router->post('/admin/testimonials', 'Admin\TestimonialController@store', ['AuthMiddleware']);
$router->put('/admin/testimonials/{id}', 'Admin\TestimonialController@update', ['AuthMiddleware']);
$router->delete('/admin/testimonials/{id}', 'Admin\TestimonialController@delete', ['AuthMiddleware']);

// Client logos management
$router->get('/admin/client-logos', 'Admin\ClientLogoController@index', ['AuthMiddleware']);
$router->post('/admin/client-logos', 'Admin\ClientLogoController@store', ['AuthMiddleware']);
$router->delete('/admin/client-logos/{id}', 'Admin\ClientLogoController@delete', ['AuthMiddleware']);

// API Routes
$router->get('/api/products', 'Api\ProductController@index');
$router->get('/api/posts', 'Api\PostController@index');
$router->post('/api/contact', 'Api\ContactController@send');

// Handle the request
try {
    $router->dispatch();
} catch (Exception $e) {
    // Log error
    error_log($e->getMessage());
    
    // Show error page
    http_response_code(500);
    if ($config['debug']) {
        echo '<h1>Error</h1>';
        echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
        echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
    } else {
        echo '<h1>Internal Server Error</h1>';
        echo '<p>Something went wrong. Please try again later.</p>';
    }
}