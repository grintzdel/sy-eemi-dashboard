<?php

declare(strict_types=1);

namespace App\Modules\Employee\Application\Commands\AddTaskToEmployee;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class AddTaskToEmployeeCommand
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public string $employeeId,

        #[Assert\NotBlank]
        #[Assert\Uuid]
        public string $taskId
    ) {}
}
