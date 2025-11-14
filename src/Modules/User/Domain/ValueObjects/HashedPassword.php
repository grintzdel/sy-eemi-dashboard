<?php

declare(strict_types=1);

namespace App\Modules\User\Domain\ValueObjects;

use Webmozart\Assert\Assert;

final readonly class HashedPassword
{
    public function __construct(
        private string $value
    )
    {
        Assert::notEmpty($this->value, 'Hashed password cannot be empty');
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
