<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Car List API
    |--------------------------------------------------------------------------
    */

    'base_url' => env('CAR_LIST_API_URL', 'https://carlistapi.com/api'),

    'version' => env('CAR_LIST_API_VERSION', 'v1'),

    'token' => env('CAR_LIST_API_TOKEN'),

    /*
    |--------------------------------------------------------------------------
    | HTTP Client
    |--------------------------------------------------------------------------
    */

    'timeout' => (int) env('CAR_LIST_API_TIMEOUT', 15),

    'connect_timeout' => (int) env('CAR_LIST_API_CONNECT_TIMEOUT', 5),

    'retry' => [
        'times' => (int) env('CAR_LIST_API_RETRY_TIMES', 2),
        'sleep_ms' => (int) env('CAR_LIST_API_RETRY_SLEEP_MS', 200),
    ],

    /*
    | Override this only when your application needs a custom User-Agent.
    | By default, the SDK generates one using its installed Composer version.
    */
    'user_agent' => env('CAR_LIST_API_USER_AGENT'),
];
