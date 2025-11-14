<?php

declare(strict_types=1);

namespace App\Modules\Task\Presentation\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Modules\Task\Application\Commands\CreateTask\CreateTaskCommand;
use App\Modules\Task\Application\Queries\GetTask\GetTaskQuery;
use App\Modules\Task\Presentation\ApiResource\TaskResource;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

final class CreateTaskProcessor implements ProcessorInterface
{
    use HandleTrait;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): TaskResource
    {
        $command = new CreateTaskCommand(
            name: $data['name'],
            description: $data['description'],
            projectId: $data['projectId'],
            status: $data['status'] ?? null,
            assignedTo: $data['assignedTo'] ?? null
        );

        $result = $this->handle($command);

        $viewModel = $this->handle(new GetTaskQuery($result['id']));

        return new TaskResource($viewModel);
    }
}
