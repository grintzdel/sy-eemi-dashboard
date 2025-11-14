<?php

declare(strict_types=1);

namespace App\Modules\Shared\Domain\ValueObjects;

use Webmozart\Assert\Assert;

final readonly class UserId
{
    public function __construct(
        private string $value
    )
    {
        Assert::uuid($value, 'User ID must be a valid UUID');
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function equals(UserId $other): bool
    {
        return $this->value === $other->value;
    }
}
