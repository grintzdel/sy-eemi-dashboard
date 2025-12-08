<?php

declare(strict_types=1);

namespace App\Modules\Shared\Domain\Enums;

enum Status: string
{
    case TODO = 'TODO';

    case ON_GOING = 'ON_GOING';

    case DONE = 'DONE';
}
