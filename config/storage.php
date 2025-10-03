<?php

return [
    'default' => $_ENV['STORAGE_DRIVER'] ?? 'local',
    
    'disks' => [
        'local' => [
            'driver' => 'local',
            'root' => __DIR__ . '/../storage/app',
        ],
        
        's3' => [
            'driver' => 's3',
            'key' => $_ENV['AWS_ACCESS_KEY_ID'] ?? '',
            'secret' => $_ENV['AWS_SECRET_ACCESS_KEY'] ?? '',
            'region' => $_ENV['AWS_DEFAULT_REGION'] ?? 'us-east-1',
            'bucket' => $_ENV['AWS_BUCKET'] ?? '',
        ],
    ],
    
    'upload' => [
        'max_size' => 10 * 1024 * 1024, // 10MB
        'allowed_types' => ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'],
    ],
];
