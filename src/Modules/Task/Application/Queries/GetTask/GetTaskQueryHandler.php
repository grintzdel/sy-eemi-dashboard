<?php

declare(strict_types=1);

namespace App\Modules\Task\Application\Queries\GetTask;

use App\Modules\Task\Application\ViewModels\TaskViewModel;
use App\Modules\Task\Domain\Exceptions\TaskNotFoundException;
use App\Modules\Task\Domain\Repositories\ITaskRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class GetTaskQueryHandler
{
    public function __construct(private ITaskRepository $taskRepository) {}

    /**
     * @throws TaskNotFoundException
     */
    public function __invoke(GetTaskQuery $query): TaskViewModel
    {
        $task = $this->taskRepository->findById($query->id);

        if ($task === null) {
            throw TaskNotFoundException::withId($query->id);
        }

        return TaskViewModel::fromEntity($task);
    }
}
