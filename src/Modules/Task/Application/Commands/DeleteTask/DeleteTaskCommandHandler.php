<?php

declare(strict_types=1);

namespace App\Modules\Task\Application\Commands\DeleteTask;

use App\Modules\Task\Domain\Exceptions\TaskNotFoundException;
use App\Modules\Task\Domain\Repositories\ITaskRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class DeleteTaskCommandHandler
{
    public function __construct(private ITaskRepository $taskRepository) {}

    /**
     * @throws TaskNotFoundException
     */
    public function execute(DeleteTaskCommand $command): array
    {
        $task = $this->taskRepository->findById($command->getId());

        if ($task === null) {
            throw TaskNotFoundException::withId($command->getId());
        }

        $this->taskRepository->delete($task);

        return ['id' => $task->getId(), 'deleted' => true];
    }

    /**
     * @throws TaskNotFoundException
     */
    public function __invoke(DeleteTaskCommand $command): array
    {
        return $this->execute($command);
    }
}
