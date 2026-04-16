<?php

declare(strict_types=1);

namespace Controllers;

use Domain\User;
use HTTP\Request;
use HTTP\Response;
use Repositories\UserRepository;
use Security\JwtHelper;

class AuthController
{
    public function __construct(private UserRepository $userRepo, private Request $request) {}

    public function login()
    {
        $data = $this->request->getJsonData();
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';
        if (!$email || !$password) {
            Response::json(['error' => 'Email and password are required'], 400);
            return;
        }
        $user = $this->userRepo->findByEmail($email);
        if (!$user || !password_verify($password, $user->password)) {
            Response::json(['error' => 'Invalid credentials'], 401);
            return;
        }
        // Generate JWT token
        $token = JwtHelper::generate([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role
        ]);
        // Set token in HttpOnly cookie
        setcookie('token', $token, [
            'httponly' => true,
            'expires' => time() + 3600,
            'samesite' => 'Lax',
            'secure' => false, // Set to true in production with HTTPS
            'path' => '/'
        ]);
        Response::json([
            'status' => 'success',
            'message' => 'Login successful',
            'user' => [
                'name' => $user->name,
                'role' => $user->role
            ]
        ]);
    }
    public function register()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $name = $data['name'] ?? '';
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';
        if (!$name || !$email || !$password) {
            Response::json(['error' => 'Name, email, and password are required'], 400);
            return;
        }
        if ($this->userRepo->findByEmail($email)) {
            Response::json(['error' => 'Email already exists'], 409);
            return;
        }
        $user = new User(
            id: null,
            name: $name,
            email: $email,
            password: $password
        );
        if ($this->userRepo->create($user)) {
            Response::json(['status' => 'success', 'message' => 'Registration successful'], 201);
        } else {
            Response::json(['error' => 'Failed to create user'], 500);
        }
    }
    public function logout()
    {
        setcookie('token', '', [
            'expires' => time() - 3600,
            'path' => '/',
            'httponly' => true,
            'samesite' => 'Lax',
            'secure' => false
        ]);
        Response::json(['status' => 'success', 'message' => 'Logged out successfully']);
    }
}
