<?php

declare(strict_types=1);

namespace Contracts;

use Domain\User;

interface UserRepositoryInterface
{
    public function findByEmail(string $email): ?User;

    public function create(User $user): bool;
}
