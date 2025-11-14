<?php

declare(strict_types=1);

namespace App\Modules\Project\Application\Commands\UpdateProject;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class UpdateProjectCommand
{
    public function __construct(
        #[Assert\NotBlank(message: 'Project ID is required')]
        public string $id,

        #[Assert\NotBlank(message: 'Project name is required')]
        #[Assert\Length(max: 255)]
        public string $name,

        #[Assert\NotBlank(message: 'Project description is required')]
        public string $description
    )
    {
    }
}
