<?php

declare(strict_types=1);

namespace App\Modules\Project\Application\Commands\RemoveTaskFromProject;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class RemoveTaskFromProjectCommand
{
    public function __construct(
        #[Assert\NotBlank(message: 'Project ID is required')]
        public string $projectId,

        #[Assert\NotBlank(message: 'Task ID is required')]
        public string $taskId
    )
    {
    }
}
