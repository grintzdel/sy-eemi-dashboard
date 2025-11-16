<?php

declare(strict_types=1);

namespace App\Modules\Analytics\Application\ViewModels;

final readonly class ProjectsByPeriodViewModel
{
    public function __construct(
        public string $period,
        public int $periodNumber,
        public string $periodLabel,
        public int $count,
        public int $year
    ) {}
}
