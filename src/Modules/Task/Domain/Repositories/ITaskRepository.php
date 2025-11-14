<?php

declare(strict_types=1);

namespace App\Modules\Task\Domain\Repositories;

use App\Modules\Task\Domain\Entities\Task;

interface ITaskRepository
{
    /*
     * Queries
     */
    public function findById(string $id): ?Task;

    public function findAll(): array;

    public function findByProjectId(string $projectId): array;

    public function findByEmployeeId(string $employeeId): array;

    /*
     * Commands
     */
    public function save(Task $task): void;

    public function delete(Task $task): void;
}
