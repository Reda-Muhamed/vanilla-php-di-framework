<?php

declare(strict_types=1);

namespace Domain;

class User
{
    public function __construct(
        public ?int $id,
        public string $name,
        public string $email,
        public string $password,
        public string $role = 'patient',
        public ?string $createdAt = null
    ) {}

    public function isAdmin(): bool
    {
        return $this->role === UserRole::ADMIN->value;
    }
}