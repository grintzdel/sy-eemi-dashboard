<?php

declare(strict_types=1);

namespace App\Modules\Project\Application\ViewModels;

use App\Modules\Shared\Domain\Enums\Status;

final readonly class ProjectViewModel
{
    public function __construct(
        public string $id,
        public string $name,
        public string $description,
        public Status $status,
        public array $taskIds,
        public \DateTimeImmutable $createdAt,
        public \DateTimeImmutable $updatedAt
    )
    {
    }
}
