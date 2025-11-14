<?php

declare(strict_types=1);

namespace App\Modules\User\Application\Queries\GetUser;

use App\Modules\Shared\Domain\ValueObjects\UserId;
use App\Modules\User\Application\ViewModels\UserViewModel;
use App\Modules\User\Domain\Exceptions\UserNotFoundException;
use App\Modules\User\Domain\Repositories\IUserRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class GetUserQueryHandler
{
    public function __construct(
        private IUserRepository $userRepository
    ) {}

    /**
     * @throws UserNotFoundException
     */
    public function __invoke(GetUserQuery $query): UserViewModel
    {
        $userId = new UserId($query->getId());
        $user = $this->userRepository->findById($userId);

        if ($user === null) {
            throw UserNotFoundException::withId($query->getId());
        }

        return UserViewModel::fromEntity($user);
    }
}
