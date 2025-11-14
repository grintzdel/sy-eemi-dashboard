<?php

declare(strict_types=1);

namespace App\Modules\Task\Application\Commands\AssignTask;

use App\Modules\Task\Domain\Exceptions\TaskNotFoundException;
use App\Modules\Task\Domain\Repositories\ITaskRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class AssignTaskCommandHandler
{
    public function __construct(private ITaskRepository $taskRepository) {}

    /**
     * @throws TaskNotFoundException
     */
    public function execute(AssignTaskCommand $command): array
    {
        $task = $this->taskRepository->findById($command->getTaskId());

        if ($task === null) {
            throw TaskNotFoundException::withId($command->getTaskId());
        }

        $task->assignToEmployee($command->getEmployeeId());

        $this->taskRepository->save($task);

        return ['id' => $task->getId(), 'assignedTo' => $task->getAssignedTo()];
    }

    /**
     * @throws TaskNotFoundException
     */
    public function __invoke(AssignTaskCommand $command): array
    {
        return $this->execute($command);
    }
}
