<?php

require_once __DIR__ . '/../bootstrap.php';

use App\Core\Router;

// Initialize router
$router = new Router();

// Load routes
require_once __DIR__ . '/../routes/web.php';

// Dispatch request
$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = $_SERVER['REQUEST_URI'];

try {
    $router->dispatch($requestMethod, $requestUri);
} catch (Exception $e) {
    if (config('app.debug')) {
        echo "<h1>Error</h1>";
        echo "<p>{$e->getMessage()}</p>";
        echo "<pre>{$e->getTraceAsString()}</pre>";
    } else {
        http_response_code(500);
        echo "500 - Internal Server Error";
    }
}
