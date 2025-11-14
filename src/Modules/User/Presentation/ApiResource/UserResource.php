<?php

declare(strict_types=1);

namespace App\Modules\User\Presentation\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Modules\User\Application\ViewModels\UserViewModel;
use App\Modules\User\Presentation\State\Processor\CreateUserProcessor;
use App\Modules\User\Presentation\State\Processor\UpdateUserProcessor;
use App\Modules\User\Presentation\State\Provider\GetUserByEmailProvider;
use App\Modules\User\Presentation\State\Provider\GetUserProvider;
use App\Modules\User\Presentation\State\Provider\ListUsersProvider;

#[ApiResource(
    shortName: 'User',
    operations: [
        new GetCollection(
            security: 'is_granted("ROLE_ADMIN") or is_granted("ROLE_MODERATOR")',
            provider: ListUsersProvider::class
        ),
        new Get(
            security: 'is_granted("ROLE_USER")',
            provider: GetUserProvider::class
        ),
        new Get(
            uriTemplate: '/users/email/{email}',
            provider: GetUserByEmailProvider::class
        ),
        new Post(
            security: 'is_granted("ROLE_ADMIN")',
            processor: CreateUserProcessor::class
        ),
        new Put(
            security: 'is_granted("ROLE_ADMIN") or object.id == user.getId()',
            processor: UpdateUserProcessor::class
        )
    ],
    routePrefix: '/users',
    paginationEnabled: false
)]
final readonly class UserResource
{
    public function __construct(
        public UserViewModel $data
    ) {}
}
