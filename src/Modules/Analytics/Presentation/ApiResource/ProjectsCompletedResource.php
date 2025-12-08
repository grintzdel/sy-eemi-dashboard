<?php

declare(strict_types=1);

namespace App\Modules\Analytics\Presentation\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use App\Modules\Analytics\Application\ViewModels\ProjectsCompletedViewModel;
use App\Modules\Analytics\Presentation\State\Provider\GetProjectsCompletedByPeriodProvider;

#[
    ApiResource(
        shortName: 'Analytics',
        operations: [
            new Get(
                uriTemplate: '/analytics/projects-completed-by-period',
                provider: GetProjectsCompletedByPeriodProvider::class,
            ),
        ],
        paginationEnabled: false,
    ),
]
final readonly class ProjectsCompletedResource
{
    public function __construct(public ProjectsCompletedViewModel $data)
    {
    }
}
