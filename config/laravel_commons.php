<?php
return [
    'logging' => [
        'request' => [
            'enable' => true,
            'path' => storage_path('logs/request.log'),
        ],
        'query' => [
            'enable' => true,
            'path' => storage_path('logs/query.log'),
        ],
        'http' => [
            'enable' => true,
            'path' => storage_path('logs/http.log'),
        ],
        'console' => [
            'enable' => true,
            'path' => storage_path('logs/console.log'),
        ],
    ],
    'log_level' => 'debug',
];
