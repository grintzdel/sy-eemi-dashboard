<?php

declare(strict_types=1);

namespace App\Modules\Task\Application\Queries\GetTask;

final readonly class GetTaskQuery
{
    public function __construct(public string $id) {}
}
