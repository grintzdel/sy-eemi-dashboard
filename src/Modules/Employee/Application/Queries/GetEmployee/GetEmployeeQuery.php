<?php

declare(strict_types=1);

namespace App\Modules\Employee\Application\Queries\GetEmployee;

final readonly class GetEmployeeQuery
{
    public function __construct(
        public string $id
    ) {}
}
