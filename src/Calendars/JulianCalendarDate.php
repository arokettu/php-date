<?php

declare(strict_types=1);

namespace Arokettu\Date\Calendars;

use Arokettu\Date\Date;
use Arokettu\Date\Month;
use Stringable;

final readonly class JulianCalendarDate implements Stringable
{
    private array $dateArray;

    public function __construct(
        public Date $date,
    ) {
    }

    public function getDateArray(): array
    {
        if (isset($this->dateArray)) {
            return $this->dateArray;
        }

        $j = $this->date->julianDay;

        // normalize to 0-700 years (255675 days)
        $c = intdiv($j, 255675);
        $j -= $c * 255675;
        // additional step to avoid int overflow on PHP_INT_MIN
        if ($j < 0) {
            $j += 255675;
            $c -= 1;
        }

        // https://en.wikipedia.org/wiki/Julian_day#Julian_or_Gregorian_calendar_from_Julian_day_number
        $f = $j + 1401;
        $e = 4 * $f + 3;
        $g = intdiv($e % 1461, 4);
        $h = 5 * $g + 2;

        $d = intdiv($h % 153, 5) + 1;
        $m = (intdiv($h, 153) + 2) % 12 + 1;
        $y = intdiv($e, 1461) - 4716 + intdiv(12 + 2 - $m, 12);

        return $this->dateArray = [$y + $c * 700, $m, $d];
    }

    public function getYear(): int
    {
        return $this->getDateArray()[0];
    }

    public function getMonth(): Month
    {
        return Month::from($this->getDateArray()[1]);
    }

    public function getMonthNumber(): int
    {
        return $this->getDateArray()[1];
    }

    public function getDay(): int
    {
        return $this->getDateArray()[2];
    }

    // string conversion

    public function toString(): string
    {
        $ymd = $this->getDateArray();
        return sprintf("%d-%02d-%02d", $ymd[0], $ymd[1], $ymd[2]);
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
