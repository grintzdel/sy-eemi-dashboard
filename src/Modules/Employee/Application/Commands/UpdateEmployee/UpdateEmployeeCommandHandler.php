<?php

declare(strict_types=1);

namespace App\Modules\Employee\Application\Commands\UpdateEmployee;

use App\Modules\Employee\Domain\Exceptions\EmployeeNotFoundException;
use App\Modules\Employee\Domain\Repositories\IEmployeeRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class UpdateEmployeeCommandHandler
{
    public function __construct(
        private IEmployeeRepository $employeeRepository
    ) {}

    /**
     * @throws EmployeeNotFoundException
     */
    public function __invoke(UpdateEmployeeCommand $command): void
    {
        $employee = $this->employeeRepository->findById($command->id);

        if ($employee === null) {
            throw new EmployeeNotFoundException($command->id);
        }

        $employee->update(
            $command->firstName,
            $command->lastName,
            $command->email,
            $command->position
        );

        $this->employeeRepository->save($employee);
    }
}
