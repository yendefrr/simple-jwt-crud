<?php
require_once __DIR__ . '/../../vendor/autoload.php';

$requestUri = $_SERVER['REQUEST_URI'];
$baseUrl = '/crud';

$requestPath = str_replace($baseUrl, '', $requestUri);
$requestParts = explode('/', $requestPath);

$controllerName = 'App\\Controllers\\' . ucfirst($requestParts[1]) . 'Controller';

if (strpos($requestParts[2], '?') !== false) {
    $parts = explode('?', $requestParts[2]);
    $methodName = $parts[0];
    $_GET['params'] = $parts[1];
} else {
    $methodName = $requestParts[2];
}

try {
    (new $controllerName())->$methodName();
} catch (\Throwable $e) {
    $controller = new App\Controllers\Controller();
    $controller->response([
        'message' => 'Not found',
        'error' => $e->getMessage(),
    ], 404);
}
