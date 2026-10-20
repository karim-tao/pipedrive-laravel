<?php

return [
    'auth' => [
        'client_id' => env('PIPEDRIVE_CLIENT_ID', ''),
        'client_secret' => env('PIPEDRIVE_CLIENT_SECRET', ''),
    ],

    'http' => [
        'timeout' => (int) env('PIPEDRIVE_HTTP_TIMEOUT', 30),
        'connect_timeout' => (int) env('PIPEDRIVE_HTTP_CONNECT_TIMEOUT', 10),
    ],
];
