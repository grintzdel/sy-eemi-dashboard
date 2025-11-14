<?php

declare(strict_types=1);

namespace App\Modules\Project\Presentation\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Modules\Project\Application\Queries\GetProject\GetProjectQuery;
use App\Modules\Project\Presentation\ApiResource\ProjectResource;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

final class GetProjectProvider implements ProviderInterface
{
    use HandleTrait;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): ?ProjectResource
    {
        $viewModel = $this->handle(new GetProjectQuery($uriVariables['id']));

        return new ProjectResource($viewModel);
    }
}
