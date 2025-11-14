<?php

declare(strict_types=1);

namespace App\Modules\Employee\Application\Queries\ListEmployees;

use App\Modules\Employee\Application\ViewModels\EmployeeViewModel;
use App\Modules\Employee\Domain\Repositories\IEmployeeRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class ListEmployeesQueryHandler
{
    public function __construct(
        private IEmployeeRepository $employeeRepository
    ) {}

    public function __invoke(ListEmployeesQuery $query): array
    {
        $employees = $this->employeeRepository->findAll();

        return array_map(
            fn($employee) => new EmployeeViewModel(
                $employee->getId(),
                $employee->getFirstName(),
                $employee->getLastName(),
                $employee->getEmail(),
                $employee->getPosition(),
                $employee->getTaskIds(),
                $employee->getCreatedAt(),
                $employee->getUpdatedAt()
            ),
            $employees
        );
    }
}
