<?php

namespace App\Core;

class Application
{
    protected $router;
    protected $request;
    protected $response;
    protected $middleware = [];
    
    public function __construct()
    {
        $this->loadEnvironment();
        $this->router = new Router();
        $this->request = new Request();
        $this->response = new Response();
        
        // Set timezone
        date_default_timezone_set(config('app.timezone', 'UTC'));
        
        // Error handling
        if (config('app.debug')) {
            error_reporting(E_ALL);
            ini_set('display_errors', 1);
        } else {
            error_reporting(0);
            ini_set('display_errors', 0);
        }
    }
    
    protected function loadEnvironment()
    {
        $envFile = __DIR__ . '/../../.env';
        if (file_exists($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos(trim($line), '#') === 0) continue;
                
                list($name, $value) = explode('=', $line, 2);
                $name = trim($name);
                $value = trim($value);
                
                if (!array_key_exists($name, $_ENV)) {
                    $_ENV[$name] = $value;
                }
            }
        }
    }
    
    public function loadMiddleware()
    {
        // Global middleware
        $this->middleware[] = new \App\Middleware\CsrfMiddleware();
        $this->middleware[] = new \App\Middleware\LoggingMiddleware();
    }
    
    public function run()
    {
        try {
            // Execute middleware
            foreach ($this->middleware as $middleware) {
                if (!$middleware->handle($this->request)) {
                    return;
                }
            }
            
            // Route the request
            $this->router->dispatch($this->request, $this->response);
            
        } catch (\App\Core\Exception\NotFoundException $e) {
            $this->response->setStatusCode(404);
            require __DIR__ . '/../../resources/views/errors/404.php';
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }
            
            $this->response->setStatusCode(500);
            require __DIR__ . '/../../resources/views/errors/500.php';
        }
    }
    
    public function getRouter()
    {
        return $this->router;
    }
}
