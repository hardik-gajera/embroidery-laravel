<?php

return [
    'sms_api' => [
        'url' => env('SMS_API_URL', 'https://api.dovesoft.io/api/json/sendsms/'),
        'key' => env('SMS_API_KEY', '1c4d918ee4XX'),
        'sender_id' => env('SMS_SENDER_ID', 'ADGAPP'),
        'entity_id' => env('SMS_ENTITY_ID', '1701177339250153619'),
        'template_id' => env('SMS_TEMPLATE_ID', '1707177389597272140'),
    ],
    
    'otp' => [
        'length' => 6,
        'expiry_minutes' => 10,
        'template' => 'Dear User, Your OTP for AARADHYA DESIGN GALLERY login is {otp}. Please do not share it with anyone. This OTP is valid for 10 minutes. ~ AARADHYA DESIGN GALLERY'
    ]
];