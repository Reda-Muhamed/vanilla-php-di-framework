<?php

declare(strict_types=1);

namespace Repositories;

use Contracts\UserRepositoryInterface;
use PDO;
use Domain\User;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(private PDO $db) {}

    public function findByEmail(string $email): ?User
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);

        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        // Map the raw database array to our pure Domain Object
        return new User(
            id: $row['id'],
            name: $row['name'],
            email: $row['email'],
            password: $row['password'],
            role: $row['role'],
            createdAt: $row['created_at']
        );
    }

    public function create(User $user): bool
    {
        $stmt = $this->db->prepare("INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)");

        return $stmt->execute([
            'name' => $user->name,
            'email' => $user->email,
            'password' => password_hash($user->password, PASSWORD_BCRYPT),
            'role' => $user->role
        ]);
    }
}
