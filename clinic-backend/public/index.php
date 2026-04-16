<?php

declare(strict_types=1);

use HTTP\Response;

// --------------------------------------------------------------------------
// 1. CORS Headers - Allow requests from the frontend
// --------------------------------------------------------------------------
$allowedOrigins = [
    'http://localhost:5500',
    // 'http://127.0.0.1:5500'
];
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';

if (in_array($origin, $allowedOrigins)) {
    
    header("Access-Control-Allow-Origin: http://localhost:5500");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
}

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// --------------------------------------------------------------------------
// 2. Boot the Application
// --------------------------------------------------------------------------
$router = require_once __DIR__ . '/../src/bootstrap.php';

// --------------------------------------------------------------------------
// 3. Dispatch the Request
// --------------------------------------------------------------------------
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

try {
    $router->dispatch($method, $uri);
} catch (\Exception $e) {
    Response::json([
        'error' => 'Internal Server Error',
        'message' => $e->getMessage()
    ], 500);
}
