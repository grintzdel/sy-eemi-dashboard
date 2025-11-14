<?php

declare(strict_types=1);

namespace App\Modules\Project\Application\Commands\CreateProject;

use App\Modules\Project\Domain\Entities\Project;
use App\Modules\Project\Domain\Repositories\IProjectRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Uid\Uuid;

#[AsMessageHandler]
final readonly class CreateProjectCommandHandler
{
    public function __construct(
        private IProjectRepository $projectRepository
    )
    {
    }

    public function __invoke(CreateProjectCommand $command): string
    {
        $project = new Project(
            Uuid::v4()->toRfc4122(),
            $command->name,
            $command->description,
            $command->taskIds ?? []
        );

        $this->projectRepository->save($project);

        return $project->getId();
    }
}
