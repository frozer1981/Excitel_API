<?php

return [
    'debug' => true,
    'use_mock_api' => true, // true за mock API, false за реалното API
    'mock_api_url' => 'http://127.0.0.1:89/mock_api.php',
    'real_api_url' => 'http://real.api.url/endpoint',
    'db' => [
        'host' => '127.0.0.1',
        'dbname' => 'excitel',
        'user' => 'root',
        'password' => '',
    ],
    'api' => [
        'url' => 'http://127.0.0.1:89/mock_api.php', // Mock API
    ],
    'log' => __DIR__ . '/../../logs/app.log', // Лог файлът е в logs/app.log
];