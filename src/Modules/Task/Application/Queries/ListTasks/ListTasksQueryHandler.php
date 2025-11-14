<?php

declare(strict_types=1);

namespace App\Modules\Task\Application\Queries\ListTasks;

use App\Modules\Task\Application\ViewModels\TaskViewModel;
use App\Modules\Task\Domain\Repositories\ITaskRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class ListTasksQueryHandler
{
    public function __construct(private ITaskRepository $taskRepository) {}

    /**
     * @return array<TaskViewModel>
     */
    public function __invoke(ListTasksQuery $query): array
    {
        $tasks = $this->taskRepository->findAll();

        return array_map(
            fn($task) => TaskViewModel::fromEntity($task),
            $tasks
        );
    }
}
