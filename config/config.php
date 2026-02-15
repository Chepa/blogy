<?php

return [
    'db' => [
        'host' => getenv('DB_HOST') ?: 'localhost',
        'port' => getenv('DB_PORT') ?: '3306',
        'name' => getenv('DB_NAME') ?: 'blogy',
        'user' => getenv('DB_USER') ?: 'blogy',
        'pass' => getenv('DB_PASS') ?: 'blogy',
    ],
    'base_url' => getenv('BASE_URL') ?: '/',
    'per_page' => 6,
];
