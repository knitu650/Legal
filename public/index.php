<?php

/**
 * Legal Document Management System
 * Entry Point
 */

// Load Composer autoloader
require_once __DIR__ . '/../vendor/autoload.php';

// Load helper functions
require_once __DIR__ . '/../app/Helpers/functions.php';

// Load constants
require_once __DIR__ . '/../config/constants.php';

use App\Core\Application;
use App\Core\Session;

// Start session
Session::start();

// Initialize application
$app = new Application();

// Load middleware
$app->loadMiddleware();

// Load routes
$router = require __DIR__ . '/../config/routes.php';

// Run application
$app->run();
