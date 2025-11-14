<?php

declare(strict_types=1);

namespace App\Modules\User\Domain\ValueObjects;

use App\Modules\User\Domain\Exceptions\InvalidEmailException;
use InvalidArgumentException;
use Webmozart\Assert\Assert;

final readonly class UserEmail
{
    public function __construct(
        private string $value
    )
    {
        try {
            Assert::notEmpty($this->value, 'Email cannot be empty');
            Assert::email($this->value, 'Email must be a valid email address');
            Assert::maxLength($this->value, 255, 'Email cannot exceed 255 characters');
        } catch (InvalidArgumentException $e) {
            throw InvalidEmailException::fromInvalidFormat($e->getMessage());
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

    public function equals(UserEmail $other): bool
    {
        return strtolower($this->value) === strtolower($other->value);
    }
}
