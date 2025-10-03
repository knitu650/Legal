<?php

return [
    'defaults' => [
        'guard' => 'web',
    ],
    
    'guards' => [
        'web' => [
            'driver' => 'session',
        ],
        'api' => [
            'driver' => 'token',
        ],
    ],
    
    'password_reset' => [
        'expire' => 60, // minutes
    ],
    
    'two_factor' => [
        'enabled' => true,
    ],
];
