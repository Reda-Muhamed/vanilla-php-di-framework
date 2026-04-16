<?php

declare(strict_types=1);

use Contracts\AppointmentRepositoryInterface;
use Contracts\UserRepositoryInterface;
use Controllers\AppointmentController;
use Controllers\AuthController;
use Core\Container;
use Core\Database;
use Dotenv\Dotenv;
use HTTP\Middleware\AuthMiddleware;
use Repositories\AppointmentRepository;
use Repositories\UserRepository;
use Core\Router;

// --------------------------------------------------------------------------
// 1. Autoloading & Env
// --------------------------------------------------------------------------
require_once __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// --------------------------------------------------------------------------
// 2. Get the container to make auto-wiring and register the bindings 
// --------------------------------------------------------------------------

$container = new Container();

$container->bind(PDO::class, fn() => Database::getInstance());
$container->bind(UserRepositoryInterface::class, UserRepository::class);
$container->bind(AppointmentRepositoryInterface::class, AppointmentRepository::class);

// --------------------------------------------------------------------------
// 3. Route Registration
// --------------------------------------------------------------------------
$router = new Router($container);

// Home Route
$router->get('/', function () {
    echo "Welcome to the Appointment API";
});
// Public Routes (Notice we pass the $authController object, not the class name)
$router->post('/api/login', [AuthController::class, 'login']);
$router->post('/api/register', [AuthController::class, 'register']);

// Protected Routes
$router->post('/api/logout', [AuthController::class, 'logout'], [AuthMiddleware::class]);
$router->get('/api/appointments', [AppointmentController::class, 'index'], [AuthMiddleware::class]);
$router->post('/api/appointments', [AppointmentController::class, 'store'], [AuthMiddleware::class]);

// Return the fully configured router
return $router;
