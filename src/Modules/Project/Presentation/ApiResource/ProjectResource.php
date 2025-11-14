<?php

declare(strict_types=1);

namespace App\Modules\Project\Presentation\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Modules\Project\Application\ViewModels\ProjectViewModel;
use App\Modules\Project\Presentation\State\Processor\CreateProjectProcessor;
use App\Modules\Project\Presentation\State\Processor\DeleteProjectProcessor;
use App\Modules\Project\Presentation\State\Processor\UpdateProjectProcessor;
use App\Modules\Project\Presentation\State\Provider\GetProjectProvider;
use App\Modules\Project\Presentation\State\Provider\ListProjectsProvider;

#[ApiResource(
    shortName: 'Project',
    operations: [
        new GetCollection(
            provider: ListProjectsProvider::class
        ),
        new Get(
            provider: GetProjectProvider::class
        ),
        new Post(
            processor: CreateProjectProcessor::class
        ),
        new Put(
            processor: UpdateProjectProcessor::class
        ),
        new Delete(
            processor: DeleteProjectProcessor::class
        )
    ],
    paginationEnabled: false
)]
final readonly class ProjectResource
{
    public function __construct(
        public ProjectViewModel $data
    ) {}
}
