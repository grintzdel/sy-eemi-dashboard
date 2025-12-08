<?php

declare(strict_types=1);

namespace App\Modules\User\Application\Services;

use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

final readonly class TempPasswordUser implements PasswordAuthenticatedUserInterface
{
    public function __construct(private string $password)
    {
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }
}
