<?php

declare(strict_types=1);

namespace App\Modules\Authentication\Domain\Exceptions;

use DomainException;

final class InvalidCredentialsException extends DomainException
{
    public static function withEmail(string $email): self
    {
        return new self(sprintf('Invalid credentials for email: %s', $email));
    }
}
