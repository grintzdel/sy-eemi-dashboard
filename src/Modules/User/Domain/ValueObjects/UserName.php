<?php

declare(strict_types=1);

namespace App\Modules\User\Domain\ValueObjects;

use App\Modules\User\Domain\Exceptions\InvalidUserNameException;
use InvalidArgumentException;
use Webmozart\Assert\Assert;

final readonly class UserName
{
    public function __construct(
        private string $value
    )
    {
        try {
            Assert::notEmpty($this->value, 'Username cannot be empty');
            Assert::minLength($this->value, 3, 'Username must be at least 3 characters');
            Assert::maxLength($this->value, 50, 'Username cannot exceed 50 characters');
        } catch (InvalidArgumentException $e) {
            throw InvalidUserNameException::fromInvalidFormat($e->getMessage());
        }
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function equals(UserName $other): bool
    {
        return $this->value === $other->value;
    }
}
