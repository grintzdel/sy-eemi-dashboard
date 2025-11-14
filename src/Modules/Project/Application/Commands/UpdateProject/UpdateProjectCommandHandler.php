<?php

declare(strict_types=1);

namespace App\Modules\Project\Application\Commands\UpdateProject;

use App\Modules\Project\Domain\Exceptions\ProjectNotFoundException;
use App\Modules\Project\Domain\Repositories\IProjectRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class UpdateProjectCommandHandler
{
    public function __construct(
        private IProjectRepository $projectRepository
    )
    {
    }

    public function __invoke(UpdateProjectCommand $command): void
    {
        $project = $this->projectRepository->findById($command->id);

        if ($project === null) {
            throw new ProjectNotFoundException($command->id);
        }

        $project->updateDetails($command->name, $command->description);

        $this->projectRepository->save($project);
    }
}
