<?php

declare(strict_types=1);

namespace App\Modules\Employee\Application\Commands\CreateEmployee;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreateEmployeeCommand
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(max: 255)]
        public string $firstName,

        #[Assert\NotBlank]
        #[Assert\Length(max: 255)]
        public string $lastName,

        #[Assert\NotBlank]
        #[Assert\Email]
        #[Assert\Length(max: 255)]
        public string $email,

        #[Assert\NotBlank]
        #[Assert\Length(max: 255)]
        public string $position,

        public array $taskIds = []
    ) {}
}
