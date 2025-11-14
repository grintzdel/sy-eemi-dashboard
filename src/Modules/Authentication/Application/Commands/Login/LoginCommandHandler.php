<?php

declare(strict_types=1);

namespace App\Modules\Authentication\Application\Commands\Login;

use App\Modules\Authentication\Application\Services\JwtTokenGenerator;
use App\Modules\Authentication\Application\ViewModels\TokenViewModel;
use App\Modules\Authentication\Domain\Exceptions\InvalidCredentialsException;
use App\Modules\User\Domain\Repositories\IUserRepository;
use App\Modules\User\Domain\ValueObjects\UserEmail;
use App\Modules\User\Infrastructure\Doctrine\Entities\UserEntity;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsMessageHandler]
final readonly class LoginCommandHandler
{
    public function __construct(
        private IUserRepository $userRepository,
        private UserPasswordHasherInterface $passwordHasher,
        private JwtTokenGenerator $tokenGenerator,
        private int $jwtTtl = 3600
    ) {}

    /**
     * @throws InvalidCredentialsException
     */
    public function __invoke(LoginCommand $command): TokenViewModel
    {
        $email = new UserEmail($command->getEmail());
        $user = $this->userRepository->findByEmail($email);

        if ($user === null || $user->isDeleted()) {
            throw InvalidCredentialsException::withEmail($command->getEmail());
        }

        // Create temporary UserEntity for password verification
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

        if (!$this->passwordHasher->isPasswordValid($userEntity, $command->getPassword())) {
            throw InvalidCredentialsException::withEmail($command->getEmail());
        }

        $token = $this->tokenGenerator->generateToken($user);

        return TokenViewModel::create($token, $this->jwtTtl);
    }
}
