<?php

declare(strict_types=1);

namespace App\Modules\Project\Domain\Repositories;

use App\Modules\Project\Domain\Entities\Project;

interface IProjectRepository
{
    public function findById(string $id): ?Project;

    public function findAll(): array;

    public function save(Project $project): void;

    public function delete(Project $project): void;
}
