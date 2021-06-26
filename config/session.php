<?php

//$commonSessionFolder = dirname(dirname(dirname(dirname(storage_path('framework/sessions'))))).'/storage/framework/sessions';

return [



    'driver' => env('SESSION_DRIVER', 'file'),


     'lifetime' => 1 * (60 * 24 * 365),

    'expire_on_close' => true,


    'encrypt' => false,



    'files' => storage_path('framework/sessions'),



    'connection' => null,

    

    'table' => 'sessions',

   

    'store' => null,

   

    'lottery' => [2, 100],


    'cookie' => env(
        'SESSION_COOKIE',
        str_slug(env('APP_NAME', 'laravel'), '_').'_session'
    ),

   

    'path' => '/',

    

    'domain' => env('SESSION_DOMAIN', 'trade.boompay.in'),
    

    'secure' => env('SESSION_SECURE_COOKIE', false),

  

    'http_only' => true,

  

    'same_site' => null,

];
