<?php

declare(strict_types=1);

namespace App\Modules\Analytics\Presentation\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use App\Modules\Analytics\Presentation\State\Provider\GetProjectsByPeriodProvider;

#[ApiResource(
    shortName: 'Analytics',
    operations: [
        new Get(
            uriTemplate: '/analytics/projects-by-period',
            provider: GetProjectsByPeriodProvider::class
        )
    ],
    paginationEnabled: false
)]
final readonly class ProjectsByPeriodResource
{
    public function __construct(
        public array $data
    ) {}
}
