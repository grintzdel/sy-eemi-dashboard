<?php

declare(strict_types=1);

namespace App\Modules\Task\Application\Commands\UpdateTask;

use App\Modules\Task\Domain\Exceptions\TaskNotFoundException;
use App\Modules\Task\Domain\Repositories\ITaskRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class UpdateTaskCommandHandler
{
    public function __construct(private ITaskRepository $taskRepository) {}

    /**
     * @throws TaskNotFoundException
     */
    public function execute(UpdateTaskCommand $command): array
    {
        $task = $this->taskRepository->findById($command->getId());

        if ($task === null) {
            throw TaskNotFoundException::withId($command->getId());
        }

        $task->setName($command->getName());
        $task->setDescription($command->getDescription());

        $this->taskRepository->save($task);

        return ['id' => $task->getId()];
    }

    /**
     * @throws TaskNotFoundException
     */
    public function __invoke(UpdateTaskCommand $command): array
    {
        return $this->execute($command);
    }
}
