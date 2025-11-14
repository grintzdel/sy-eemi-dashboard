<?php

declare(strict_types=1);

namespace App\Modules\Employee\Application\ViewModels;

final readonly class EmployeeViewModel
{
    public function __construct(
        public string $id,
        public string $firstName,
        public string $lastName,
        public string $email,
        public string $position,
        public array $taskIds,
        public \DateTimeImmutable $createdAt,
        public \DateTimeImmutable $updatedAt
    )
    {
    }
}
