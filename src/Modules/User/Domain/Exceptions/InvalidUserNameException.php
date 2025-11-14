<?php

declare(strict_types=1);

namespace App\Modules\User\Domain\Exceptions;

use DomainException;

final class InvalidUserNameException extends DomainException
{
    public static function fromInvalidFormat(string $reason): self
    {
        return new self(sprintf('Invalid username: %s', $reason));
    }
}
