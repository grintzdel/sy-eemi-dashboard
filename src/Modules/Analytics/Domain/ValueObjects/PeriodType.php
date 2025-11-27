<?php

declare(strict_types=1);

namespace App\Modules\Analytics\Domain\ValueObjects;

enum PeriodType: string
{
    case MONTH = "month";
    case WEEK = "week";
    case DAY = "day";
    case YEAR = "year";

    public static function default(): self
    {
        return self::MONTH;
    }

    public static function fromString(?string $value): self
    {
        if ($value === null) {
            return self::default();
        }

        return self::from($value);
    }

    public function getSqlGroupByExpression(string $dateColumn): string
    {
        return match ($this) {
            self::MONTH => "YEAR({$dateColumn}), MONTH({$dateColumn})",
            self::WEEK => "YEAR({$dateColumn}), WEEK({$dateColumn})",
            self::DAY => "DATE({$dateColumn})",
            self::YEAR => "YEAR({$dateColumn})",
        };
    }

    public function getSqlSelectExpression(string $dateColumn): string
    {
        return match ($this) {
            self::MONTH
                => "MONTH({$dateColumn}) as period_number, YEAR({$dateColumn}) as year",
            self::WEEK
                => "WEEK({$dateColumn}) as period_number, YEAR({$dateColumn}) as year",
            self::DAY
                => "DAY({$dateColumn}) as period_number, MONTH({$dateColumn}) as month, YEAR({$dateColumn}) as year",
            self::YEAR => "YEAR({$dateColumn}) as period_number",
        };
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::MONTH => "Monthly",
            self::WEEK => "Weekly",
            self::DAY => "Daily",
            self::YEAR => "Yearly",
        };
    }
}
