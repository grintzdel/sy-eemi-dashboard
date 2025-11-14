<?php

declare(strict_types=1);

namespace App\Modules\Task\Application\Commands\AssignTask;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class AssignTaskCommand
{
    #[Assert\NotBlank(message: 'Task ID is required')]
    private string $taskId;

    #[Assert\NotBlank(message: 'Employee ID is required')]
    private string $employeeId;

    public function __construct(string $taskId, string $employeeId)
    {
        $this->taskId = $taskId;
        $this->employeeId = $employeeId;
    }

    public function getTaskId(): string
    {
        return $this->taskId;
    }

    public function getEmployeeId(): string
    {
        return $this->employeeId;
    }
}
