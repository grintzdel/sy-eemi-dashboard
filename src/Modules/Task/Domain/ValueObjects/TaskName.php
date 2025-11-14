<?php

declare(strict_types=1);

namespace App\Modules\Task\Domain\ValueObjects;

use Webmozart\Assert\Assert;

final readonly class TaskName
{
    private string $value;

    public function __construct(string $value)
    {
        Assert::notEmpty($value, 'Task name cannot be empty');
        Assert::maxLength($value, 255, 'Task name cannot exceed 255 characters');

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
