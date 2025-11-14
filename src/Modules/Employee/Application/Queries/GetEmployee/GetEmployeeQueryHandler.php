<?php

declare(strict_types=1);

namespace App\Modules\Employee\Application\Queries\GetEmployee;

use App\Modules\Employee\Application\ViewModels\EmployeeViewModel;
use App\Modules\Employee\Domain\Exceptions\EmployeeNotFoundException;
use App\Modules\Employee\Domain\Repositories\IEmployeeRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class GetEmployeeQueryHandler
{
    public function __construct(
        private IEmployeeRepository $employeeRepository
    ) {}

    /**
     * @throws EmployeeNotFoundException
     */
    public function __invoke(GetEmployeeQuery $query): EmployeeViewModel
    {
        $employee = $this->employeeRepository->findById($query->id);

        if ($employee === null) {
            throw new EmployeeNotFoundException($query->id);
        }

        return new EmployeeViewModel(
            $employee->getId(),
            $employee->getFirstName(),
            $employee->getLastName(),
            $employee->getEmail(),
            $employee->getPosition(),
            $employee->getTaskIds(),
            $employee->getCreatedAt(),
            $employee->getUpdatedAt()
        );
    }
}
