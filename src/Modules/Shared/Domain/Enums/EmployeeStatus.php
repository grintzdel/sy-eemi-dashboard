<?php

declare(strict_types=1);

namespace App\Modules\Shared\Domain\Enums;

enum EmployeeStatus: string
{
    case ACTIVE = 'ACTIVE';

    case INACTIVE = 'INACTIVE';
}
