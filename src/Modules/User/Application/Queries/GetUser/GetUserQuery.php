<?php

declare(strict_types=1);

namespace App\Modules\User\Application\Queries\GetUser;

final readonly class GetUserQuery
{
    public function __construct(
        private string $id
    ) {}

    public function getId(): string
    {
        return $this->id;
    }
}
