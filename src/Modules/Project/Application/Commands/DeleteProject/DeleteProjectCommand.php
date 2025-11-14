<?php

declare(strict_types=1);

namespace App\Modules\Project\Application\Commands\DeleteProject;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class DeleteProjectCommand
{
    public function __construct(
        #[Assert\NotBlank(message: 'Project ID is required')]
        public string $id
    )
    {
    }
}
