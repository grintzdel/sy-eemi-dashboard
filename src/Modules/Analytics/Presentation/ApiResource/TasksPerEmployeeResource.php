<?php

declare(strict_types=1);

namespace App\Modules\Analytics\Presentation\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use App\Modules\Analytics\Presentation\State\Provider\GetTasksPerEmployeeProvider;

#[
    ApiResource(
        shortName: "Analytics",
        operations: [
            new Get(
                uriTemplate: "/analytics/tasks-per-employee",
                provider: GetTasksPerEmployeeProvider::class,
            ),
        ],
        paginationEnabled: false,
    ),
]
final readonly class TasksPerEmployeeResource
{
    public function __construct(public array $data) {}
}
