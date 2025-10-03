<?php

return [
    'google_maps_api_key' => $_ENV['GOOGLE_MAPS_API_KEY'] ?? '',
    
    'states' => [
        'MH' => 'Maharashtra',
        'DL' => 'Delhi',
        'KA' => 'Karnataka',
        'TN' => 'Tamil Nadu',
        'GJ' => 'Gujarat',
        'RJ' => 'Rajasthan',
        'UP' => 'Uttar Pradesh',
        'WB' => 'West Bengal',
        'TG' => 'Telangana',
        'AP' => 'Andhra Pradesh',
    ],
    
    'stamp_duty' => [
        'MH' => 5.0, // percentage
        'DL' => 6.0,
        'KA' => 5.5,
        'TN' => 7.0,
        'GJ' => 4.9,
    ],
    
    'default_currency' => 'INR',
    'currency_symbol' => '₹',
];
