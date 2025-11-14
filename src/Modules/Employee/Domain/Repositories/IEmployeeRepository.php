<?php

declare(strict_types=1);

namespace App\Modules\Employee\Domain\Repositories;

use App\Modules\Employee\Domain\Entities\Employee;

interface IEmployeeRepository
{
    /*
     * Queries
     */
    public function findById(string $id): ?Employee;

    public function findAll(): array;

    /*
     * Commands
     */
    public function save(Employee $employee): void;

    public function delete(Employee $employee): void;
}
