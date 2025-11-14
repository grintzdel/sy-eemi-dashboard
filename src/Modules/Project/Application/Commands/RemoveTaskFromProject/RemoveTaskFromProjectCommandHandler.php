<?php

declare(strict_types=1);

namespace App\Modules\Project\Application\Commands\RemoveTaskFromProject;

use App\Modules\Project\Domain\Exceptions\ProjectNotFoundException;
use App\Modules\Project\Domain\Repositories\IProjectRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class RemoveTaskFromProjectCommandHandler
{
    public function __construct(
        private IProjectRepository $projectRepository
    )
    {
    }

    public function __invoke(RemoveTaskFromProjectCommand $command): void
    {
        $project = $this->projectRepository->findById($command->projectId);

        if ($project === null) {
            throw new ProjectNotFoundException($command->projectId);
        }

        $project->removeTask($command->taskId);

        $this->projectRepository->save($project);
    }
}
