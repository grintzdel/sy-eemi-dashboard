<?php

declare(strict_types=1);

namespace App\Modules\Task\Presentation\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Modules\Task\Application\Queries\GetTask\GetTaskQuery;
use App\Modules\Task\Presentation\ApiResource\TaskResource;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

final class GetTaskProvider implements ProviderInterface
{
    use HandleTrait;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): TaskResource
    {
        $viewModel = $this->handle(new GetTaskQuery($uriVariables['id']));

        return new TaskResource($viewModel);
    }
}
