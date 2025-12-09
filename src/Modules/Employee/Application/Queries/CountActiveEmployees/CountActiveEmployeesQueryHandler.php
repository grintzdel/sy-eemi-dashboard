<?php

declare(strict_types=1);

namespace App\Modules\Employee\Application\Queries\CountActiveEmployees;

use App\Modules\Employee\Domain\Repositories\IEmployeeRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class CountActiveEmployeesQueryHandler
{
    public function __construct(
        private IEmployeeRepository $employeeRepository,
    ) {
    }

    public function __invoke(CountActiveEmployeesQuery $query): int
    {
        return $this->employeeRepository->countActiveEmployees();
    }
}
