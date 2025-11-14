<?php

declare(strict_types=1);

namespace App\Modules\Project\Domain\Exceptions;

class ProjectNotFoundException extends \Exception
{
    public function __construct(string $projectId)
    {
        parent::__construct(sprintf('Project with ID "%s" not found.', $projectId));
    }
}
