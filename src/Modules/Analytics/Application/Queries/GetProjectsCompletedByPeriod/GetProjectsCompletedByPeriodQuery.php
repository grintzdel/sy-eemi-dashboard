<?php

declare(strict_types=1);

namespace App\Modules\Analytics\Application\Queries\GetProjectsCompletedByPeriod;

use App\Modules\Analytics\Domain\ValueObjects\PeriodType;

final readonly class GetProjectsCompletedByPeriodQuery
{
    public function __construct(
        public PeriodType $period = PeriodType::MONTH,
        public ?int $year = null,
    ) {
    }
}
