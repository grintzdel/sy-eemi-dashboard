<?php

declare(strict_types=1);

namespace App\Modules\Analytics\Application\Queries\GetProjectsByPeriod;

use App\Modules\Analytics\Domain\ValueObjects\PeriodType;

final readonly class GetProjectsByPeriodQuery
{
    public function __construct(
        public PeriodType $period = PeriodType::MONTH,
        public ?int $year = null
    ) {}
}
