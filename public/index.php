<?php

require_once __DIR__ . '/../vendor/autoload.php';
use App\Helpers\Config;

Config::load(__DIR__ . '/../app/Config/config.php');

$container = require __DIR__ . '/../app/Config/dependencies.php';

// Извличане на контролера чрез контейнера
$controller = $container->get(App\Controllers\ApiController::class);

// Обработка на маршрути
$uri = trim($_SERVER['REQUEST_URI'], '/');
$method = $_SERVER['REQUEST_METHOD'];

switch ($uri) {
    case 'sync':
        if ($method === 'GET') {
            $controller->sync();
        } else {
            http_response_code(405);
            echo json_encode(['error' => 'Method Not Allowed']);
        }
        break;

    case 'mock_api.php':
        if ($method === 'GET') {
            require __DIR__ . '/../mock/mock_api.php';
        } else {
            http_response_code(405);
            echo json_encode(['error' => 'Method Not Allowed']);
        }
        break;

    default:
        http_response_code(404);
        echo json_encode(['error' => 'Not Found']);
        break;
}
