<?php

declare(strict_types=1);

namespace App\Modules\User\Application\Queries\GetUserByEmail;

final readonly class GetUserByEmailQuery
{
    public function __construct(
        private string $email
    ) {}

    public function getEmail(): string
    {
        return $this->email;
    }
}
