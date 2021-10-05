<?php
    
return [
    'app_name'      => env('APP_NAME', 'App'),
    'app_env'       => env('APP_ENV', 'dev'),
    'page_limit'    => env('PAGE_LIMIT', 10),
    'boolean_true'  => 1,
    'boolean_false' => 0,
    'enable_mail'   => env('ENABLE_EMAIL', 0),
    'otp_length'    => env('OTP_LENGTH', 6),
    
    // OTP expiry
    'verification_otp_expiry'   => env('VERIFICATION_OTP_EXPIRY', 60), // Minutes
    'password_otp_expiry'       => env('PASSWORD_OTP_EXPIRY', 60), // Minutes

    // Http status codes
    'status_code'   => [
       'ok'         => 200,
       'created'    => 201,
       'not_found'  => 404, 
    ],
];