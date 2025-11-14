<?php

declare(strict_types=1);

namespace App\Modules\Project\Application\Queries\GetProject;

use App\Modules\Project\Application\ViewModels\ProjectViewModel;
use App\Modules\Project\Domain\Exceptions\ProjectNotFoundException;
use App\Modules\Project\Domain\Repositories\IProjectRepository;
use App\Modules\Task\Domain\Repositories\ITaskRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class GetProjectQueryHandler
{
    public function __construct(
        private IProjectRepository $projectRepository,
        private ITaskRepository $taskRepository
    )
    {
    }

    public function __invoke(GetProjectQuery $query): ProjectViewModel
    {
        $project = $this->projectRepository->findById($query->id);

        if ($project === null) {
            throw new ProjectNotFoundException($query->id);
        }

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
    }
}
