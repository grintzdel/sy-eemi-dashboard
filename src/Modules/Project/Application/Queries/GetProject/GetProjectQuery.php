<?php

declare(strict_types=1);

namespace App\Modules\Project\Application\Queries\GetProject;

final readonly class GetProjectQuery
{
    public function __construct(
        public string $id
    )
    {
    }
}
