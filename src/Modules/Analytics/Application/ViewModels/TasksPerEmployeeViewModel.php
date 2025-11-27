<?php

declare(strict_types=1);

namespace App\Modules\Analytics\Application\ViewModels;

final readonly class TasksPerEmployeeViewModel
{
    public function __construct(
        public string $employeeId,
        public string $firstName,
        public string $lastName,
        public string $email,
        public int $totalTasks,
        public int $completedTasks,
        public int $inProgressTasks,
        public int $todoTasks,
        public float $completionRate,
    ) {}
}
