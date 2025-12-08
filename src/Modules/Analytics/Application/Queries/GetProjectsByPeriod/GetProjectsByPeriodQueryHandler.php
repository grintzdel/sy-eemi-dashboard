<?php

declare(strict_types=1);

namespace App\Modules\Analytics\Application\Queries\GetProjectsByPeriod;

use App\Modules\Analytics\Application\ViewModels\ProjectsByPeriodViewModel;
use App\Modules\Analytics\Domain\ValueObjects\PeriodType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class GetProjectsByPeriodQueryHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(GetProjectsByPeriodQuery $query): array
    {
        $year = $query->year ?? (int) date('Y');
        $period = $query->period;

        $sql = $this->buildSqlQuery($period, $year);

        $stmt = $this->entityManager->getConnection()->prepare($sql);
        $result = $stmt->executeQuery();

        return $this->mapResults(
            $result->fetchAllAssociative(),
            $period,
            $year,
        );
    }

    private function buildSqlQuery(PeriodType $period, int $year): string
    {
        $selectExpression = $period->getSqlSelectExpression('created_at');
        $groupByExpression = $period->getSqlGroupByExpression('created_at');

        return <<<SQL
            SELECT
                {$selectExpression},
                COUNT(*) as count
            FROM projects
            WHERE deleted_at IS NULL
                AND YEAR(created_at) = {$year}
            GROUP BY {$groupByExpression}
            ORDER BY year, period_number
        SQL;
    }

    private function mapResults(
        array $results,
        PeriodType $period,
        int $year,
    ): array {
        return array_map(
            fn (array $row) => new ProjectsByPeriodViewModel(
                period: $period->value,
                periodNumber: (int) $row['period_number'],
                periodLabel: $this->getPeriodLabel(
                    $period,
                    (int) $row['period_number'],
                ),
                count: (int) $row['count'],
                year: (int) ($row['year'] ?? $year),
            ),
            $results,
        );
    }

    private function getPeriodLabel(
        PeriodType $period,
        int $periodNumber,
    ): string {
        return match ($period) {
            PeriodType::MONTH => $this->getMonthName($periodNumber),
            PeriodType::WEEK => "Week {$periodNumber}",
            PeriodType::DAY => "Day {$periodNumber}",
            PeriodType::YEAR => "Year {$periodNumber}",
        };
    }

    private function getMonthName(int $month): string
    {
        $months = [
            1 => 'January',
            2 => 'February',
            3 => 'March',
            4 => 'April',
            5 => 'May',
            6 => 'June',
            7 => 'July',
            8 => 'August',
            9 => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December',
        ];

        return $months[$month] ?? "Month {$month}";
    }
}
