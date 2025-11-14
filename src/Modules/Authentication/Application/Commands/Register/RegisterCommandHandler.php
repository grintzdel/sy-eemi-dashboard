<?php

declare(strict_types=1);

namespace App\Modules\Authentication\Application\Commands\Register;

use App\Modules\Authentication\Application\Services\JwtTokenGenerator;
use App\Modules\Authentication\Application\ViewModels\TokenViewModel;
use App\Modules\Shared\Application\Ports\Services\IIdProvider;
use App\Modules\Shared\Domain\Enums\Roles;
use App\Modules\Shared\Domain\ValueObjects\UserId;
use App\Modules\User\Domain\Entities\User;
use App\Modules\User\Domain\Exceptions\EmailAlreadyExistsException;
use App\Modules\User\Domain\Exceptions\InvalidEmailException;
use App\Modules\User\Domain\Exceptions\InvalidUserNameException;
use App\Modules\User\Domain\Repositories\IUserRepository;
use App\Modules\User\Domain\ValueObjects\HashedPassword;
use App\Modules\User\Domain\ValueObjects\UserEmail;
use App\Modules\User\Domain\ValueObjects\UserName;
use App\Modules\User\Infrastructure\Services\EmailUniquenessService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[AsMessageHandler]
final readonly class RegisterCommandHandler
{
    public function __construct(
        private IIdProvider $idProvider,
        private IUserRepository $userRepository,
        private EmailUniquenessService $emailUniquenessService,
        private UserPasswordHasherInterface $passwordHasher,
        private JwtTokenGenerator $tokenGenerator,
        private int $jwtTtl = 3600
    ) {}

    /**
     * @throws InvalidUserNameException
     * @throws InvalidEmailException
     * @throws EmailAlreadyExistsException
     */
    public function __invoke(RegisterCommand $command): TokenViewModel
    {
        $email = new UserEmail($command->getEmail());

        $this->emailUniquenessService->ensureEmailIsUnique($email);

        $hashedPasswordValue = $this->passwordHasher->hashPassword(
            new readonly class($command->getPassword()) implements PasswordAuthenticatedUserInterface {
                public function __construct(private string $password) {}
                public function getPassword(): ?string { return $this->password; }
            },
            $command->getPassword()
        );

        $user = User::create(
            id: new UserId($this->idProvider->generateId()),
            name: new UserName($command->getName()),
            email: $email,
            password: new HashedPassword($hashedPasswordValue),
            role: Roles::from($command->getRole())
        );

        $this->userRepository->save($user);

        $token = $this->tokenGenerator->generateToken($user);

        return TokenViewModel::create($token, $this->jwtTtl);
    }
}
