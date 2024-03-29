<?php

declare(strict_types=1);

namespace Arokettu\Date;

use Arokettu\Date\Helpers\YearHelper;

enum Month: int
{
    case January = 1;
    case February = 2;
    case March = 3;
    case April = 4;
    case May = 5;
    case June = 6;
    case July = 7;
    case August = 8;
    case September = 9;
    case October = 10;
    case November = 11;
    case December = 12;

    public function days(int $year): int
    {
        return match ($this) {
            self::January, self::March, self::May, self::July, self::August, self::October, self::December,
                => 31,
            self::April, self::June, self::September, self::November,
                => 30,
            self::February,
                => YearHelper::isLeap($year) ? 29 : 28,
        };
    }

    public function julianDays(int $year): int
    {
        return match ($this) {
            self::January, self::March, self::May, self::July, self::August, self::October, self::December,
                => 31,
            self::April, self::June, self::September, self::November,
                => 30,
            self::February,
                => YearHelper::isJulianLeap($year) ? 29 : 28,
        };
    }
}
