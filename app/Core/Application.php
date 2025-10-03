<?php
namespace App\Core;

class Application {
    protected static $instance;
    protected $config;
    protected $request;
    protected $response;
    protected $session;
    protected $router;
    protected $db;

    public function __construct() {
        self::$instance = $this;
        $this->loadEnvironment();
        $this->initializeComponents();
    }

    protected function loadEnvironment() {
        $envFile = __DIR__ . '/../../.env';
        if (file_exists($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
                    list($key, $value) = explode('=', $line, 2);
                    $_ENV[trim($key)] = trim($value);
                    putenv(trim($key) . '=' . trim($value));
                }
            }
        }
    }

    protected function initializeComponents() {
        // Initialize core components
        $this->request = new Request();
        $this->response = new Response();
        $this->session = new Session();
        $this->router = new Router();
        $this->db = new Database();

        // Load configuration
        $this->loadConfig();
    }

    protected function loadConfig() {
        // Load all configuration files
        $configPath = __DIR__ . '/../../config/';
        $configFiles = glob($configPath . '*.php');
        
        foreach ($configFiles as $file) {
            $key = basename($file, '.php');
            $this->config[$key] = require $file;
        }
    }

    public function run() {
        try {
            // Start session if not already started
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            // Process the request through middleware
            $response = $this->router->dispatch($this->request);

            // Send the response
            $this->response->send($response);

        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }

    public function handleException(\Exception $e) {
        // Log the error
        error_log($e->getMessage());

        if (getenv('APP_DEBUG') === 'true') {
            // Show detailed error in debug mode
            $this->response->setStatusCode(500);
            $this->response->setContent($e->getMessage() . "\n" . $e->getTraceAsString());
        } else {
            // Show generic error in production
            $this->response->setStatusCode(500);
            $this->response->setContent('An internal server error occurred.');
        }

        $this->response->send();
    }

    public static function getInstance() {
        return self::$instance;
    }

    public function getConfig($key = null) {
        if ($key === null) {
            return $this->config;
        }
        return $this->config[$key] ?? null;
    }

    public function getRequest() {
        return $this->request;
    }

    public function getResponse() {
        return $this->response;
    }

    public function getSession() {
        return $this->session;
    }

    public function getRouter() {
        return $this->router;
    }

    public function getDb() {
        return $this->db;
    }
}