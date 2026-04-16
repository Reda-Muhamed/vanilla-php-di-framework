<?php

declare(strict_types=1);

namespace HTTP\Middleware;

use HTTP\Response;
use Security\JwtHelper;

class AuthMiddleware
{
    public static function handle(): void
    {
        $token = $_COOKIE['token'] ?? null;
        if (!$token) {
            Response::json(['error' => 'Unauthorized - No token provided'], 401);
        }
        $payload = JwtHelper::verify($token);
        if (!$payload) {
            Response::json(['error' => 'Unauthorized - Invalid token'], 401);
        }
        // Store user info in the global server variable for later use in controllers
        $_SERVER['AUTHENTICATED_USER'] = $payload;
    }
}
