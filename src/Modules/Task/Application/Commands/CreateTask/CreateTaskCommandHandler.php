<?php

declare(strict_types=1);

namespace App\Modules\Task\Application\Commands\CreateTask;

use App\Modules\Shared\Application\Ports\Services\IIdProvider;
use App\Modules\Shared\Domain\Enums\Status;
use App\Modules\Task\Domain\Entities\Task;
use App\Modules\Task\Domain\Repositories\ITaskRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class CreateTaskCommandHandler
{
    public function __construct(
        private IIdProvider $idProvider,
        private ITaskRepository $taskRepository
    ) {}

    public function execute(CreateTaskCommand $command): array
    {
        $taskId = $this->idProvider->generateId();

        $status = $command->getStatus()
            ? Status::from($command->getStatus())
            : Status::TODO;

        $task = new Task(
            $taskId,
            $command->getName(),
            $command->getDescription(),
            $command->getProjectId(),
            $status,
            $command->getAssignedTo() ?? []
        );

        $this->taskRepository->save($task);

        return ['id' => $taskId];
    }

    public function __invoke(CreateTaskCommand $command): array
    {
        return $this->execute($command);
    }
}
