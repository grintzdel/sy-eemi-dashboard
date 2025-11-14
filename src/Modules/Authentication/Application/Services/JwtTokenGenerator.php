<?php

declare(strict_types=1);

namespace App\Modules\Authentication\Application\Services;

use App\Modules\User\Domain\Entities\User;
use App\Modules\User\Infrastructure\Doctrine\Entities\UserEntity;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

final readonly class JwtTokenGenerator
{
    public function __construct(
        private JWTTokenManagerInterface $jwtManager
    ) {}

    public function generateToken(User $user): string
    {
        $userEntity = new UserEntity(
            id: $user->getId()->getValue(),
            name: $user->getName()->getValue(),
            email: $user->getEmail()->getValue(),
            password: $user->getPassword()->getValue(),
            role: $user->getRole()->value,
            createdAt: $user->getCreatedAt(),
            updatedAt: $user->getUpdatedAt(),
            deletedAt: $user->getDeletedAt()
        );

        return $this->jwtManager->create($userEntity);
    }
}
