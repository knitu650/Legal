<?php

return [
    'driver' => $_ENV['SMS_DRIVER'] ?? 'twilio',
    
    'twilio' => [
        'sid' => $_ENV['SMS_SID'] ?? '',
        'token' => $_ENV['SMS_TOKEN'] ?? '',
        'from' => $_ENV['SMS_FROM'] ?? '',
    ],
    
    'msg91' => [
        'auth_key' => $_ENV['MSG91_AUTH_KEY'] ?? '',
        'sender_id' => $_ENV['MSG91_SENDER_ID'] ?? '',
    ],
];
