<?php

declare(strict_types=1);

namespace App\Modules\Task\Application\ViewModels;

use App\Modules\Task\Domain\Entities\Task;

final readonly class TaskViewModel
{
    /**
     * @param array<string> $assignedTo
     */
    public function __construct(
        public string $id,
        public string $name,
        public string $description,
        public string $projectId,
        public string $status,
        public array $assignedTo,
        public string $createdAt,
        public string $updatedAt,
        public ?string $deletedAt
    ) {}

    public static function fromEntity(Task $task): self
    {
        return new self(
            id: $task->getId(),
            name: $task->getName(),
            description: $task->getDescription(),
            projectId: $task->getProjectId(),
            status: $task->getStatus()->value,
            assignedTo: $task->getAssignedTo(),
            createdAt: $task->getCreatedAt()->format('Y-m-d H:i:s'),
            updatedAt: $task->getUpdatedAt()->format('Y-m-d H:i:s'),
            deletedAt: $task->getDeletedAt()?->format('Y-m-d H:i:s')
        );
    }
}
