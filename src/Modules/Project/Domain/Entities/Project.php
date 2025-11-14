<?php

declare(strict_types=1);

namespace App\Modules\Project\Domain\Entities;

use App\Modules\Shared\Domain\Enums\StatusEnum;

class Project
{
    private array $taskIds;
    private \DateTimeImmutable $updatedAt;
    private ?\DateTimeImmutable $deletedAt = null;

    public function __construct(
        private readonly string $id,
        private string $name,
        private string $description,
        array $taskIds = [],
        private readonly \DateTimeImmutable $createdAt = new \DateTimeImmutable()
    )
    {
        $this->taskIds = $taskIds;
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getTaskIds(): array
    {
        return $this->taskIds;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function getDeletedAt(): ?\DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function updateDetails(string $name, string $description): void
    {
        $this->name = $name;
        $this->description = $description;
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function addTask(string $taskId): void
    {
        if (!in_array($taskId, $this->taskIds, true)) {
            $this->taskIds[] = $taskId;
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function removeTask(string $taskId): void
    {
        $this->taskIds = array_values(array_filter(
            $this->taskIds,
            fn(string $id) => $id !== $taskId
        ));
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function delete(): void
    {
        $this->deletedAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function calculateStatus(array $taskStatuses): StatusEnum
    {
        if (empty($taskStatuses)) {
            return StatusEnum::TODO;
        }

        $allDone = true;
        $hasInProgress = false;

        foreach ($taskStatuses as $status) {
            if ($status === StatusEnum::ON_GOING) {
                $hasInProgress = true;
                $allDone = false;
            } elseif ($status === StatusEnum::TODO) {
                $allDone = false;
            }
        }

        if ($allDone) {
            return StatusEnum::DONE;
        }

        if ($hasInProgress) {
            return StatusEnum::ON_GOING;
        }

        return StatusEnum::TODO;
    }
}
