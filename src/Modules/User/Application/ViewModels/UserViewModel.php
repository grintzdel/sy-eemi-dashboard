<?php

declare(strict_types=1);

namespace App\Modules\User\Application\ViewModels;

use App\Modules\User\Domain\Entities\User;

final readonly class UserViewModel
{
    public function __construct(
        public string $id,
        public string $name,
        public string $email,
        public string $role,
        public string $createdAt,
        public string $updatedAt
    ) {}

    public static function fromEntity(User $user): self
    {
        return new self(
            id: $user->getId()->getValue(),
            name: $user->getName()->getValue(),
            email: $user->getEmail()->getValue(),
            role: $user->getRole()->value,
            createdAt: $user->getCreatedAt()->format('Y-m-d H:i:s'),
            updatedAt: $user->getUpdatedAt()->format('Y-m-d H:i:s')
        );
    }
}
