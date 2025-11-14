<?php

declare(strict_types=1);

namespace App\Modules\Task\Domain\Exceptions;

final class TaskNotFoundException extends \Exception
{
    public static function withId(string $id): self
    {
        return new self(sprintf('Task with id "%s" not found', $id));
    }
}
