<?php 
return [ 
    'client_id' => env('PAYPAL_CLIENT_ID','AVL0L948gureLANsdruyGxZ-resociaVVu3T8Nwasdf-iSIpEBpFNptUtpDLNbMYH9KeJWbQlnVi_HCc'),
    'secret' => env('PAYPAL_SECRET','ELF9rm_K0AdsCA4qR7cIZbmdtdX6yZxoDu1HEs1u3897TBBZKxRCi9LlyDCPbfGn_gxD5W9V_kI2sZbM'),
    'settings' => array(
        'mode' => env('PAYPAL_MODE','live'),
        'http.ConnectionTimeOut' => 300,
        'log.LogEnabled' => true,
        'log.FileName' => storage_path() . '/logs/paypal.log',
        'log.LogLevel' => 'ERROR'
    ),
];
