<?php

declare(strict_types=1);

namespace App\Modules\User\Presentation\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Modules\User\Application\Queries\GetUserByEmail\GetUserByEmailQuery;
use App\Modules\User\Presentation\ApiResource\UserResource;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

final class GetUserByEmailProvider implements ProviderInterface
{
    use HandleTrait;

    public function __construct(
        MessageBusInterface $messageBus
    )
    {
        $this->messageBus = $messageBus;
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): UserResource
    {
        $viewModel = $this->handle(new GetUserByEmailQuery($uriVariables['email']));

        return new UserResource($viewModel);
    }
}
