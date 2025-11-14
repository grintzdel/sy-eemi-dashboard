<?php

declare(strict_types=1);

namespace App\Modules\User\Domain\Exceptions;

use DomainException;

final class EmailAlreadyExistsException extends DomainException
{
    public static function withEmail(string $email): self
    {
        return new self(sprintf('A user with email "%s" already exists', $email));
    }
}
