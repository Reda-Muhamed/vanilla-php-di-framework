<?php

declare(strict_types=1);

namespace Core;

use HTTP\Response;

class Router
{
    public function __construct(private Container $container) {}

    private array $routes = [];
    public function get(string $path, array|callable $handler, array $middleware = [])
    {
        $this->routes['GET'][$path] = [
            'handler' => $handler,
            'middleware' => $middleware
        ];
    }

    public function post(string $path, array|callable $handler, array $middleware = [])
    {
        $this->routes['POST'][$path] = [
            'handler' => $handler,
            'middleware' => $middleware
        ];
    }

    public function dispatch(string $method, string $uri): void
    {

        if (!isset($this->routes[$method][$uri])) {

            // If no route matches, return a 404 response
            Response::json(['error' => 'Route not found'], 404);
        }

        $routeInfo = $this->routes[$method][$uri];
        $middlewares = $routeInfo['middleware'] ?? [];
        $handler = $routeInfo['handler'];

        // Execute middleware in order
        foreach ($middlewares as $mw) {

            if (is_string($mw)) {

                $middlewareInstance = $this->container->make($mw);
                $middlewareInstance->handle();
            } else {

                //if it already an object was sent
                call_user_func([$mw, 'handle']);
            }
        }

        // if middleware did not kill the script, execute the route handler

        if (is_array($handler)) { //[AuthController::class, 'login']
            $className = $handler[0];
            $methodName = $handler[1];
            if (is_string($className)) {
                $controllerInstance = $this->container->make($className);
                $handler = [$controllerInstance, $methodName];
            }
        }
        call_user_func($handler);
        return;
    }
}
