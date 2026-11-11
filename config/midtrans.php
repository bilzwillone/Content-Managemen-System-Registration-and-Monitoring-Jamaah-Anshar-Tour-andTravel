<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Midtrans Credentials
    |--------------------------------------------------------------------------
    */

    'server_key' => env('MIDTRANS_SERVER_KEY'),
    
    'signature_key' => env('MIDTRANS_SIGNATURE_KEY'),

    'client_key' => env('MIDTRANS_CLIENT_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Environment
    |--------------------------------------------------------------------------
    |
    | false = Sandbox
    | true  = Production
    |
    */

    'is_production' => (bool) env('MIDTRANS_IS_PRODUCTION', false),

    /*
    |--------------------------------------------------------------------------
    | Transaction Security
    |--------------------------------------------------------------------------
    */

    'is_sanitized' => true,

    'is_3ds' => true,

];