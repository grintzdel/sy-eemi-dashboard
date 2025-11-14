<?php

declare(strict_types=1);

namespace App\Modules\User\Presentation\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Modules\User\Application\Queries\GetUser\GetUserQuery;
use App\Modules\User\Presentation\ApiResource\UserResource;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

final class GetUserProvider implements ProviderInterface
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
        $viewModel = $this->handle(new GetUserQuery($uriVariables['id']));

        return new UserResource($viewModel);
    }
}
