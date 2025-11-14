<?php

declare(strict_types=1);

namespace App\Modules\Task\Domain\ValueObjects;

use Webmozart\Assert\Assert;

final readonly class TaskDescription
{
    private string $value;

    public function __construct(string $value)
    {
        Assert::maxLength($value, 5000, 'Task description cannot exceed 5000 characters');

        $this->value = $value;
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
