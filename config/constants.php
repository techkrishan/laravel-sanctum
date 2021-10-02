<?php
    
return [
    'app_name'      => env('APP_NAME', 'App'),
    'app_env'       => env('APP_ENV', 'dev'),
    'page_limit'    => env('PAGE_LIMIT', 10),
    'boolean_true'  => 1,
    'boolean_false' => 0,

    // Http status codes
    'status_code'   => [
       'ok'         => 200,
       'created'    => 201,
       'not_found'  => 404, 
    ],
];