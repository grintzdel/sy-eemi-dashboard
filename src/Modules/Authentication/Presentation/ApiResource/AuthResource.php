<?php

declare(strict_types=1);

namespace App\Modules\Authentication\Presentation\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Modules\Authentication\Application\DTO\LoginDTO;
use App\Modules\Authentication\Application\DTO\RegisterDTO;
use App\Modules\Authentication\Application\ViewModels\TokenViewModel;
use App\Modules\Authentication\Presentation\State\Processor\LoginProcessor;
use App\Modules\Authentication\Presentation\State\Processor\RegisterProcessor;

#[ApiResource(
    shortName: 'Auth',
    operations: [
        new Post(
            uriTemplate: '/auth/register',
            security: 'true',
            input: RegisterDTO::class,
            output: TokenViewModel::class,
            processor: RegisterProcessor::class
        ),
        new Post(
            uriTemplate: '/auth/login',
            security: 'true',
            input: LoginDTO::class,
            output: TokenViewModel::class,
            processor: LoginProcessor::class
        )
    ],
    routePrefix: '',
    paginationEnabled: false
)]
final readonly class AuthResource
{
}
