<?php

declare(strict_types=1);

namespace App\Modules\Employee\Application\Commands\AddTaskToEmployee;

use App\Modules\Employee\Domain\Exceptions\EmployeeNotFoundException;
use App\Modules\Employee\Domain\Repositories\IEmployeeRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class AddTaskToEmployeeCommandHandler
{
    public function __construct(
        private IEmployeeRepository $employeeRepository
    ) {}

    /**
     * @throws EmployeeNotFoundException
     */
    public function __invoke(AddTaskToEmployeeCommand $command): void
    {
        $employee = $this->employeeRepository->findById($command->employeeId);

        if ($employee === null) {
            throw new EmployeeNotFoundException($command->employeeId);
        }

        $employee->addTask($command->taskId);

        $this->employeeRepository->save($employee);
    }
}
