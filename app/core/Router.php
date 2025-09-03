<?php

namespace App\Core;

/**
 * Router Class
 * Handles URL routing and controller dispatching
 */
class Router
{
    private $routes = [];
    private $middleware = [];
    private $currentRoute = null;

    /**
     * Add a GET route
     */
    public function get($path, $handler, $middleware = [])
    {
        $this->addRoute('GET', $path, $handler, $middleware);
    }

    /**
     * Add a POST route
     */
    public function post($path, $handler, $middleware = [])
    {
        $this->addRoute('POST', $path, $handler, $middleware);
    }

    /**
     * Add a PUT route
     */
    public function put($path, $handler, $middleware = [])
    {
        $this->addRoute('PUT', $path, $handler, $middleware);
    }

    /**
     * Add a DELETE route
     */
    public function delete($path, $handler, $middleware = [])
    {
        $this->addRoute('DELETE', $path, $handler, $middleware);
    }

    /**
     * Add a route
     */
    private function addRoute($method, $path, $handler, $middleware = [])
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler,
            'middleware' => $middleware
        ];
    }

    /**
     * Dispatch the request
     */
    public function dispatch()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = $this->getCurrentUri();

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $this->matchRoute($route['path'], $uri)) {
                $this->currentRoute = $route;
                
                // Execute middleware
                if (!empty($route['middleware'])) {
                    foreach ($route['middleware'] as $middleware) {
                        if (!$this->executeMiddleware($middleware)) {
                            return false;
                        }
                    }
                }

                // Execute handler
                return $this->executeHandler($route['handler'], $this->getRouteParams($route['path'], $uri));
            }
        }

        // 404 Not Found
        $this->handleNotFound();
        return false;
    }

    /**
     * Get current URI
     */
    private function getCurrentUri()
    {
        $uri = $_SERVER['REQUEST_URI'];
        
        // Remove query string
        if (($pos = strpos($uri, '?')) !== false) {
            $uri = substr($uri, 0, $pos);
        }

        // Remove base path if exists
        $basePath = dirname($_SERVER['SCRIPT_NAME']);
        if ($basePath !== '/' && strpos($uri, $basePath) === 0) {
            $uri = substr($uri, strlen($basePath));
        }

        return $uri ?: '/';
    }

    /**
     * Match route pattern with URI
     */
    private function matchRoute($pattern, $uri)
    {
        // Convert route pattern to regex
        $pattern = preg_replace('/\{([^}]+)\}/', '([^/]+)', $pattern);
        $pattern = '#^' . $pattern . '$#';

        return preg_match($pattern, $uri);
    }

    /**
     * Get route parameters
     */
    private function getRouteParams($pattern, $uri)
    {
        $params = [];
        
        // Extract parameter names from pattern
        preg_match_all('/\{([^}]+)\}/', $pattern, $paramNames);
        
        // Convert pattern to regex and extract values
        $regex = preg_replace('/\{([^}]+)\}/', '([^/]+)', $pattern);
        $regex = '#^' . $regex . '$#';
        
        if (preg_match($regex, $uri, $matches)) {
            array_shift($matches); // Remove full match
            $params = array_combine($paramNames[1], $matches);
        }

        return $params;
    }

    /**
     * Execute route handler
     */
    private function executeHandler($handler, $params = [])
    {
        if (is_string($handler)) {
            // Controller@method format
            if (strpos($handler, '@') !== false) {
                list($controller, $method) = explode('@', $handler);
                $controllerClass = "App\\Controllers\\{$controller}";
                
                if (class_exists($controllerClass)) {
                    $controllerInstance = new $controllerClass();
                    if (method_exists($controllerInstance, $method)) {
                        return call_user_func_array([$controllerInstance, $method], $params);
                    }
                }
            }
        } elseif (is_callable($handler)) {
            return call_user_func_array($handler, $params);
        }

        throw new \Exception("Invalid route handler");
    }

    /**
     * Execute middleware
     */
    private function executeMiddleware($middleware)
    {
        if (is_string($middleware)) {
            $middlewareClass = "App\\Middleware\\{$middleware}";
            if (class_exists($middlewareClass)) {
                $middlewareInstance = new $middlewareClass();
                return $middlewareInstance->handle();
            }
        } elseif (is_callable($middleware)) {
            return call_user_func($middleware);
        }

        return true;
    }

    /**
     * Handle 404 Not Found
     */
    private function handleNotFound()
    {
        http_response_code(404);
        
        // Check if custom 404 view exists
        $viewPath = __DIR__ . '/../views/errors/404.php';
        if (file_exists($viewPath)) {
            include $viewPath;
        } else {
            echo '<h1>404 - Page Not Found</h1>';
        }
    }

    /**
     * Generate URL for a named route
     */
    public function url($path, $params = [])
    {
        $url = $path;
        
        foreach ($params as $key => $value) {
            $url = str_replace('{' . $key . '}', $value, $url);
        }

        return $url;
    }

    /**
     * Redirect to URL
     */
    public function redirect($url, $statusCode = 302)
    {
        header("Location: {$url}", true, $statusCode);
        exit;
    }
}