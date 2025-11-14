<?php

declare(strict_types=1);

namespace App\Modules\Employee\Domain\Exceptions;

class EmployeeNotFoundException extends \Exception
{
    public function __construct(string $employeeId)
    {
        parent::__construct(sprintf('Employee with ID "%s" not found.', $employeeId));
    }
}
