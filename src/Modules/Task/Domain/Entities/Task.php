<?php

declare(strict_types=1);

namespace App\Modules\Task\Domain\Entities;

use App\Modules\Shared\Domain\Enums\Status;

class Task
{
    /** @var array<string> */
    private array $assignedTo;

    private \DateTimeImmutable $updatedAt;

    private ?\DateTimeImmutable $deletedAt = null;

    /**
     * @param array<string> $assignedTo
     */
    public function __construct(
        private readonly string             $id,
        private string                      $name,
        private string                      $description,
        private string                      $projectId,
        private Status                      $status = Status::TODO,
        array                               $assignedTo = [],
        private readonly \DateTimeImmutable $createdAt = new \DateTimeImmutable()
    )
    {
        $this->assignedTo = $assignedTo;
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

    public function setName(string $name): void
    {
        $this->name = $name;
        $this->touch();
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
        $this->touch();
    }

    public function getProjectId(): string
    {
        return $this->projectId;
    }

    public function setProjectId(string $projectId): void
    {
        $this->projectId = $projectId;
        $this->touch();
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function setStatus(Status $status): void
    {
        $this->status = $status;
        $this->touch();
    }

    /**
     * @return array<string>
     */
    public function getAssignedTo(): array
    {
        return $this->assignedTo;
    }

    public function assignToEmployee(string $employeeId): void
    {
        if (!in_array($employeeId, $this->assignedTo, true)) {
            $this->assignedTo[] = $employeeId;
            $this->touch();
        }
    }

    public function unassignFromEmployee(string $employeeId): void
    {
        $this->assignedTo = array_values(
            array_filter($this->assignedTo, fn($id) => $id !== $employeeId)
        );
        $this->touch();
    }

    public function isAssignedTo(string $employeeId): bool
    {
        return in_array($employeeId, $this->assignedTo, true);
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

    public function delete(): void
    {
        $this->deletedAt = new \DateTimeImmutable();
    }

    public function isDeleted(): bool
    {
        return $this->deletedAt !== null;
    }

    private function touch(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }
}
