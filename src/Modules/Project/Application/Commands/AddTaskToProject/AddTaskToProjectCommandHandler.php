<?php

declare(strict_types=1);

namespace App\Modules\Project\Application\Commands\AddTaskToProject;

use App\Modules\Project\Domain\Exceptions\ProjectNotFoundException;
use App\Modules\Project\Domain\Repositories\IProjectRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class AddTaskToProjectCommandHandler
{
    public function __construct(
        private IProjectRepository $projectRepository
    )
    {
    }

    public function __invoke(AddTaskToProjectCommand $command): void
    {
        $project = $this->projectRepository->findById($command->projectId);

        if ($project === null) {
            throw new ProjectNotFoundException($command->projectId);
        }

        $project->addTask($command->taskId);

        $this->projectRepository->save($project);
    }
}
