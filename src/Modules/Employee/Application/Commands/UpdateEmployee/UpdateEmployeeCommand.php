<?php

declare(strict_types=1);

namespace App\Modules\Employee\Application\Commands\UpdateEmployee;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class UpdateEmployeeCommand
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public string $id,

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
        public string $position
    ) {}
}
