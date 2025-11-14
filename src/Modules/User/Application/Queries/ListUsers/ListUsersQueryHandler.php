<?php

declare(strict_types=1);

namespace App\Modules\User\Application\Queries\ListUsers;

use App\Modules\User\Application\ViewModels\UserViewModel;
use App\Modules\User\Domain\Repositories\IUserRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class ListUsersQueryHandler
{
    public function __construct(
        private IUserRepository $userRepository
    ) {}

    public function __invoke(ListUsersQuery $query): array
    {
        $users = $this->userRepository->findAll();

        return array_map(
            fn($user) => UserViewModel::fromEntity($user),
            $users
        );
    }
}
