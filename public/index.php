<?php
session_start();
require_once '../config.php';

spl_autoload_register(function($className) {
    require_once '../app/' . str_replace('\\', '/', $className) . '.php';
});

$router = new Core\Router();

// Define routes
$router->add('', ['controller' => 'Home', 'action' => 'index']);

// Property routes
$router->add('property', ['controller' => 'Property', 'action' => 'index']);
$router->add('property/rental', ['controller' => 'Property', 'action' => 'rental']);
$router->add('property/commercial', ['controller' => 'Property', 'action' => 'commercial']);
$router->add('property/poa', ['controller' => 'Property', 'action' => 'poa']);

// Personal routes
$router->add('personal', ['controller' => 'Personal', 'action' => 'index']);
$router->add('personal/poa', ['controller' => 'Personal', 'action' => 'poa']);
$router->add('personal/will', ['controller' => 'Personal', 'action' => 'will']);
$router->add('personal/affidavit', ['controller' => 'Personal', 'action' => 'affidavit']);

// Business routes
$router->add('business', ['controller' => 'Business', 'action' => 'index']);
$router->add('business/nda', ['controller' => 'Business', 'action' => 'nda']);
$router->add('business/employment', ['controller' => 'Business', 'action' => 'employment']);
$router->add('business/consultancy', ['controller' => 'Business', 'action' => 'consultancy']);

$router->dispatch($_SERVER['QUERY_STRING']);
?>