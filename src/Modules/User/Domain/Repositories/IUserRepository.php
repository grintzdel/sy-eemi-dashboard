<?php

declare(strict_types=1);

namespace App\Modules\User\Domain\Repositories;

use App\Modules\Shared\Domain\ValueObjects\UserId;
use App\Modules\User\Domain\Entities\User;
use App\Modules\User\Domain\ValueObjects\UserEmail;

interface IUserRepository
{
    /*
     * Queries
     */
    public function findById(UserId $id): ?User;

    public function findByEmail(UserEmail $email): ?User;

    public function findAll(): array;

    /*
     * Commands
     */
    public function save(User $user): void;

    public function delete(UserId $id): void;
}
