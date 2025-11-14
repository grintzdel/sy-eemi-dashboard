<?php

declare(strict_types=1);

namespace App\Modules\User\Domain\Entities;

use App\Modules\Shared\Domain\Enums\Roles;
use App\Modules\Shared\Domain\ValueObjects\UserId;
use App\Modules\User\Domain\ValueObjects\HashedPassword;
use App\Modules\User\Domain\ValueObjects\UserEmail;
use App\Modules\User\Domain\ValueObjects\UserName;
use DateTimeImmutable;

final class User
{
    private function __construct(
        private readonly UserId            $id,
        private UserName                   $name,
        private UserEmail                  $email,
        private HashedPassword             $password,
        private Roles                      $role,
        private readonly DateTimeImmutable $createdAt,
        private DateTimeImmutable          $updatedAt,
        private ?DateTimeImmutable         $deletedAt = null
    ) {}

    public static function create(
        UserId $id,
        UserName $name,
        UserEmail $email,
        HashedPassword $password,
        Roles $role = Roles::ROLE_USER
    ): self
    {
        $now = new DateTimeImmutable();

        return new self(
            id: $id,
            name: $name,
            email: $email,
            password: $password,
            role: $role,
            createdAt: $now,
            updatedAt: $now
        );
    }

    public static function reconstitute(
        UserId $id,
        UserName $name,
        UserEmail $email,
        HashedPassword $password,
        Roles $role,
        DateTimeImmutable $createdAt,
        DateTimeImmutable $updatedAt,
        ?DateTimeImmutable $deletedAt = null
    ): self
    {
        return new self(
            id: $id,
            name: $name,
            email: $email,
            password: $password,
            role: $role,
            createdAt: $createdAt,
            updatedAt: $updatedAt,
            deletedAt: $deletedAt
        );
    }

    public function update(
        UserName $name,
        UserEmail $email
    ): void
    {
        $this->name = $name;
        $this->email = $email;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function updateRole(Roles $role): void
    {
        $this->role = $role;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function updatePassword(HashedPassword $password): void
    {
        $this->password = $password;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function delete(): void
    {
        $this->deletedAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
    }

    public function isDeleted(): bool
    {
        return $this->deletedAt !== null;
    }

    public function getId(): UserId
    {
        return $this->id;
    }

    public function getName(): UserName
    {
        return $this->name;
    }

    public function getEmail(): UserEmail
    {
        return $this->email;
    }

    public function getPassword(): HashedPassword
    {
        return $this->password;
    }

    public function getRole(): Roles
    {
        return $this->role;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function getDeletedAt(): ?DateTimeImmutable
    {
        return $this->deletedAt;
    }
}
