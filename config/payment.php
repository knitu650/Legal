<?php

return [
    'default' => 'razorpay',
    
    'razorpay' => [
        'key' => $_ENV['RAZORPAY_KEY'] ?? '',
        'secret' => $_ENV['RAZORPAY_SECRET'] ?? '',
    ],
    
    'stripe' => [
        'key' => $_ENV['STRIPE_KEY'] ?? '',
        'secret' => $_ENV['STRIPE_SECRET'] ?? '',
    ],
    
    'paytm' => [
        'merchant_id' => $_ENV['PAYTM_MID'] ?? '',
        'merchant_key' => $_ENV['PAYTM_KEY'] ?? '',
        'website' => $_ENV['PAYTM_WEBSITE'] ?? 'WEBSTAGING',
        'industry_type' => $_ENV['PAYTM_INDUSTRY_TYPE'] ?? 'Retail',
    ],
    
    'currency' => 'INR',
];
