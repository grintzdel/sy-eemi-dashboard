<?php

declare(strict_types=1);

namespace App\Modules\User\Application\Queries\GetUserByEmail;

use App\Modules\User\Application\ViewModels\UserViewModel;
use App\Modules\User\Domain\Exceptions\UserNotFoundException;
use App\Modules\User\Domain\Repositories\IUserRepository;
use App\Modules\User\Domain\ValueObjects\UserEmail;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class GetUserByEmailQueryHandler
{
    public function __construct(
        private IUserRepository $userRepository
    ) {}

    /**
     * @throws UserNotFoundException
     */
    public function __invoke(GetUserByEmailQuery $query): UserViewModel
    {
        $userEmail = new UserEmail($query->getEmail());
        $user = $this->userRepository->findByEmail($userEmail);

        if ($user === null) {
            throw UserNotFoundException::withEmail($query->getEmail());
        }

        return UserViewModel::fromEntity($user);
    }
}
