<?php

return [
    'driver' => $_ENV['SESSION_DRIVER'] ?? 'file',
    'lifetime' => $_ENV['SESSION_LIFETIME'] ?? 120, // minutes
    'path' => '/',
    'domain' => null,
    'secure' => false,
    'http_only' => true,
    'same_site' => 'lax',
];
