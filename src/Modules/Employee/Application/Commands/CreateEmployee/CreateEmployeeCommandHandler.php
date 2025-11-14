<?php

declare(strict_types=1);

namespace App\Modules\Employee\Application\Commands\CreateEmployee;

use App\Modules\Employee\Domain\Entities\Employee;
use App\Modules\Employee\Domain\Repositories\IEmployeeRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Uid\Uuid;

#[AsMessageHandler]
final readonly class CreateEmployeeCommandHandler
{
    public function __construct(
        private IEmployeeRepository $employeeRepository
    ) {}

    public function __invoke(CreateEmployeeCommand $command): string
    {
        $employee = new Employee(
            Uuid::v4()->toRfc4122(),
            $command->firstName,
            $command->lastName,
            $command->email,
            $command->position,
            $command->taskIds
        );

        $this->employeeRepository->save($employee);

        return $employee->getId();
    }
}
