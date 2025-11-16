<?php

declare(strict_types=1);

namespace App\Modules\Analytics\Presentation\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Modules\Analytics\Application\Queries\GetTasksPerEmployee\GetTasksPerEmployeeQuery;
use App\Modules\Analytics\Presentation\ApiResource\TasksPerEmployeeResource;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

final class GetTasksPerEmployeeProvider implements ProviderInterface
{
    use HandleTrait;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): TasksPerEmployeeResource
    {
        $query = new GetTasksPerEmployeeQuery();

        $viewModels = $this->handle($query);

        return new TasksPerEmployeeResource(data: $viewModels);
    }
}
