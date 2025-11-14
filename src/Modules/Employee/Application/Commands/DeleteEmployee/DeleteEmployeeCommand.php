<?php

declare(strict_types=1);

namespace App\Modules\Employee\Application\Commands\DeleteEmployee;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class DeleteEmployeeCommand
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public string $id
    ) {}
}
