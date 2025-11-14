<?php

declare(strict_types=1);

namespace App\Modules\Employee\Domain\Entities;

final class Employee
{
    private \DateTimeImmutable $updatedAt;
    private ?\DateTimeImmutable $deletedAt = null;

    public function __construct(
        private readonly string $id,
        private string $firstName,
        private string $lastName,
        private string $email,
        private string $position,
        private array $taskIds = [],
        private readonly \DateTimeImmutable $createdAt = new \DateTimeImmutable()
    ) {
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPosition(): string
    {
        return $this->position;
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

    public function update(string $firstName, string $lastName, string $email, string $position): void
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->position = $position;
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
        $index = array_search($taskId, $this->taskIds, true);
        if ($index !== false) {
            array_splice($this->taskIds, $index, 1);
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function delete(): void
    {
        $this->deletedAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function isDeleted(): bool
    {
        return $this->deletedAt !== null;
    }
}
