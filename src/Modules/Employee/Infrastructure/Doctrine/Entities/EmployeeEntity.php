<?php

declare(strict_types=1);

namespace App\Modules\Employee\Infrastructure\Doctrine\Entities;

use App\Modules\Shared\Domain\Enums\EmployeeStatus;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'employees')]
class EmployeeEntity
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 36)]
    private string $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $firstName;

    #[ORM\Column(type: 'string', length: 255)]
    private string $lastName;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private string $email;

    #[ORM\Column(type: 'string', length: 255)]
    private string $position;

    #[ORM\Column(type: 'string', length: 500)]
    private string $avatar;

    #[ORM\Column(type: 'json')]
    private array $taskIds = [];

    #[ORM\Column(type: 'string', enumType: EmployeeStatus::class)]
    private EmployeeStatus $status;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $updatedAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $deletedAt = null;

    public function __construct(
        string $id,
        string $firstName,
        string $lastName,
        string $email,
        string $position,
        string $avatar,
        array $taskIds,
        EmployeeStatus $status,
        \DateTimeImmutable $createdAt,
        \DateTimeImmutable $updatedAt,
    ) {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->position = $position;
        $this->avatar = $avatar;
        $this->taskIds = $taskIds;
        $this->status = $status;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPosition(): string
    {
        return $this->position;
    }

    public function setPosition(string $position): void
    {
        $this->position = $position;
    }

    public function getAvatar(): string
    {
        return $this->avatar;
    }

    public function setAvatar(string $avatar): void
    {
        $this->avatar = $avatar;
    }

    public function getTaskIds(): array
    {
        return $this->taskIds;
    }

    public function setTaskIds(array $taskIds): void
    {
        $this->taskIds = $taskIds;
    }

    public function getStatus(): EmployeeStatus
    {
        return $this->status;
    }

    public function setStatus(EmployeeStatus $status): void
    {
        $this->status = $status;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getDeletedAt(): ?\DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?\DateTimeImmutable $deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }
}
