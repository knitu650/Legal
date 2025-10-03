<?php

namespace App\Core;

class Router
{
    protected $routes = [
        'GET' => [],
        'POST' => [],
        'PUT' => [],
        'DELETE' => [],
    ];
    
    protected $groupPrefix = '';
    protected $groupMiddleware = [];
    
    public function get($path, $handler)
    {
        $this->addRoute('GET', $path, $handler);
    }
    
    public function post($path, $handler)
    {
        $this->addRoute('POST', $path, $handler);
    }
    
    public function put($path, $handler)
    {
        $this->addRoute('PUT', $path, $handler);
    }
    
    public function delete($path, $handler)
    {
        $this->addRoute('DELETE', $path, $handler);
    }
    
    protected function addRoute($method, $path, $handler)
    {
        $path = $this->groupPrefix . $path;
        $path = '/' . trim($path, '/');
        
        $this->routes[$method][$path] = [
            'handler' => $handler,
            'middleware' => $this->groupMiddleware,
        ];
    }
    
    public function group($attributes, $callback)
    {
        $previousPrefix = $this->groupPrefix;
        $previousMiddleware = $this->groupMiddleware;
        
        if (isset($attributes['prefix'])) {
            $this->groupPrefix = $previousPrefix . '/' . trim($attributes['prefix'], '/');
        }
        
        if (isset($attributes['middleware'])) {
            $middleware = is_array($attributes['middleware']) 
                ? $attributes['middleware'] 
                : explode(',', $attributes['middleware']);
            $this->groupMiddleware = array_merge($previousMiddleware, $middleware);
        }
        
        $callback($this);
        
        $this->groupPrefix = $previousPrefix;
        $this->groupMiddleware = $previousMiddleware;
    }
    
    public function dispatch(Request $request, Response $response)
    {
        $method = $request->getMethod();
        $uri = $request->getUri();
        
        // Check for exact match
        if (isset($this->routes[$method][$uri])) {
            $route = $this->routes[$method][$uri];
            return $this->executeRoute($route, [], $request, $response);
        }
        
        // Check for parameterized routes
        foreach ($this->routes[$method] as $path => $route) {
            $pattern = $this->convertToRegex($path);
            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches); // Remove full match
                return $this->executeRoute($route, $matches, $request, $response);
            }
        }
        
        throw new Exception\NotFoundException("Route not found: $method $uri");
    }
    
    protected function convertToRegex($path)
    {
        $path = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([a-zA-Z0-9_-]+)', $path);
        return '#^' . $path . '$#';
    }
    
    protected function executeRoute($route, $params, Request $request, Response $response)
    {
        // Execute route middleware
        foreach ($route['middleware'] as $middlewareName) {
            $middlewareClass = "App\\Middleware\\" . ucfirst($middlewareName) . "Middleware";
            if (class_exists($middlewareClass)) {
                $middleware = new $middlewareClass();
                if (!$middleware->handle($request)) {
                    return;
                }
            }
        }
        
        // Parse handler
        if (is_string($route['handler'])) {
            list($controller, $method) = explode('@', $route['handler']);
            $controllerClass = "App\\Controllers\\$controller";
            
            if (!class_exists($controllerClass)) {
                throw new \Exception("Controller not found: $controllerClass");
            }
            
            $controllerInstance = new $controllerClass($request, $response);
            
            if (!method_exists($controllerInstance, $method)) {
                throw new \Exception("Method not found: $method in $controllerClass");
            }
            
            return call_user_func_array([$controllerInstance, $method], $params);
        }
        
        // Closure handler
        return call_user_func_array($route['handler'], $params);
    }
}
