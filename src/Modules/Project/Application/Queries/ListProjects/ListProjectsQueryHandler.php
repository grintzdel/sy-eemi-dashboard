<?php

declare(strict_types=1);

namespace App\Modules\Project\Application\Queries\ListProjects;

use App\Modules\Project\Application\ViewModels\ProjectViewModel;
use App\Modules\Project\Domain\Repositories\IProjectRepository;
use App\Modules\Task\Domain\Repositories\ITaskRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class ListProjectsQueryHandler
{
    public function __construct(
        private IProjectRepository $projectRepository,
        private ITaskRepository $taskRepository
    )
    {
    }

    public function __invoke(ListProjectsQuery $query): array
    {
        $projects = $this->projectRepository->findAll();

        return array_map(function ($project) {
            $taskStatuses = [];
            foreach ($project->getTaskIds() as $taskId) {
                $task = $this->taskRepository->findById($taskId);
                if ($task !== null) {
                    $taskStatuses[] = $task->getStatus();
                }
            }

            $status = $project->calculateStatus($taskStatuses);

            return new ProjectViewModel(
                $project->getId(),
                $project->getName(),
                $project->getDescription(),
                $status,
                $project->getTaskIds(),
                $project->getCreatedAt(),
                $project->getUpdatedAt()
            );
        }, $projects);
    }
}
