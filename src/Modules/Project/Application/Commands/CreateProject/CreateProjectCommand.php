<?php

declare(strict_types=1);

namespace App\Modules\Project\Application\Commands\CreateProject;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreateProjectCommand
{
    public function __construct(
        #[Assert\NotBlank(message: 'Project name is required')]
        #[Assert\Length(max: 255)]
        public string $name,

        #[Assert\NotBlank(message: 'Project description is required')]
        public string $description,

        public ?array $taskIds = []
    )
    {
    }
}
