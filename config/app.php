<?php

return [
    'name' => $_ENV['APP_NAME'] ?? 'Legal Document Management System',
    'env' => $_ENV['APP_ENV'] ?? 'production',
    'debug' => $_ENV['APP_DEBUG'] ?? false,
    'url' => $_ENV['APP_URL'] ?? 'http://localhost',
    'timezone' => $_ENV['APP_TIMEZONE'] ?? 'Asia/Kolkata',
    
    'encryption_key' => $_ENV['ENCRYPTION_KEY'] ?? '',
    
    'providers' => [
        // Service Providers
    ],
    
    'aliases' => [
        // Class Aliases
    ],
];
