<?php

declare(strict_types=1);

namespace App\Modules\Task\Application\Commands\UpdateTaskStatus;

use App\Modules\Shared\Domain\Enums\Status;
use App\Modules\Task\Domain\Exceptions\TaskNotFoundException;
use App\Modules\Task\Domain\Repositories\ITaskRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class UpdateTaskStatusCommandHandler
{
    public function __construct(private ITaskRepository $taskRepository) {}

    /**
     * @throws TaskNotFoundException
     */
    public function execute(UpdateTaskStatusCommand $command): array
    {
        $task = $this->taskRepository->findById($command->getId());

        if ($task === null) {
            throw TaskNotFoundException::withId($command->getId());
        }

        $task->setStatus(Status::from($command->getStatus()));

        $this->taskRepository->save($task);

        return ['id' => $task->getId(), 'status' => $task->getStatus()->value];
    }

    /**
     * @throws TaskNotFoundException
     */
    public function __invoke(UpdateTaskStatusCommand $command): array
    {
        return $this->execute($command);
    }
}
