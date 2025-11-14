<?php

declare(strict_types=1);

namespace App\Modules\User\Application\Commands\UpdateUser;

use App\Modules\Shared\Domain\ValueObjects\UserId;
use App\Modules\User\Application\ViewModels\UserViewModel;
use App\Modules\User\Domain\Exceptions\EmailAlreadyExistsException;
use App\Modules\User\Domain\Exceptions\InvalidEmailException;
use App\Modules\User\Domain\Exceptions\InvalidUserNameException;
use App\Modules\User\Domain\Exceptions\UserNotFoundException;
use App\Modules\User\Domain\Repositories\IUserRepository;
use App\Modules\User\Domain\ValueObjects\UserEmail;
use App\Modules\User\Domain\ValueObjects\UserName;
use App\Modules\User\Infrastructure\Services\EmailUniquenessService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class UpdateUserCommandHandler
{
    public function __construct(
        private IUserRepository $userRepository,
        private EmailUniquenessService $emailUniquenessService
    ) {}

    /**
     * @throws InvalidUserNameException
     * @throws InvalidEmailException
     * @throws EmailAlreadyExistsException
     * @throws UserNotFoundException
     */
    public function __invoke(UpdateUserCommand $command): UserViewModel
    {
        $userId = new UserId($command->getId());
        $user = $this->userRepository->findById($userId);

        if ($user === null) {
            throw UserNotFoundException::withId($command->getId());
        }

        $email = new UserEmail($command->getEmail());

        $this->emailUniquenessService->ensureEmailIsUnique($email, $command->getId());

        $user->update(
            name: new UserName($command->getName()),
            email: $email
        );

        $this->userRepository->save($user);

        return UserViewModel::fromEntity($user);
    }
}
