<?php

declare(strict_types=1);

namespace Arokettu\Date\Calendars;

use Arokettu\Date\Helpers\YearHelper;
use Arokettu\Date\WeekDay;
use Stringable;

final readonly class IsoWeekDate implements Stringable
{
    private const Y400_DAYS = 146097;
    private const Y400_YEARS = 400;
    private const Y4_DAYS = 1461;
    private const Y4_YEARS = 4;
    private const BASE_DAY = 1721060; // 0-1-1 Gregorian

    private array $dateArray;

    public function __construct(
        public int $julianDay,
    ) {
    }

    public function getDateArray(): array
    {
        if (isset($this->dateArray)) {
            return $this->dateArray;
        }

        $j = $this->julianDay;

        // normalize to 0-400 years (146097 days)
        $cycle = intdiv($j, self::Y400_DAYS) - 13;
        if ($cycle >= 0) {
            $j -= $cycle * self::Y400_DAYS;
        } else {
            // prevent int_min overflow
            $c1 = intdiv($cycle, 2);
            $c2 = $cycle - $c1;
            $j -= $c1 * self::Y400_DAYS;
            $j -= $c2 * self::Y400_DAYS;
        }

        // use a slightly different Gregorian algorithm that gives us day of the year as an intermediate step
        $dayFromBase = $j - self::BASE_DAY;
        $century = intdiv(self::Y4_YEARS * $dayFromBase - 1, self::Y400_DAYS);
        $dayOfCentury = $dayFromBase - intdiv(self::Y400_DAYS * $century, 4);

        $yearOfCentury = intdiv(self::Y4_YEARS * $dayOfCentury, self::Y4_DAYS);
        $dayOfYear = $dayOfCentury - intdiv(self::Y4_DAYS * $yearOfCentury - 1, self::Y4_YEARS);

        $year = 100 * $century + $yearOfCentury;

        $weekDay = $j % 7 + 1;
        $week = intdiv($dayOfYear - $weekDay + 10, 7);
        $weeks = YearHelper::weeksInIsoYear($year);
        if ($week < 1) {
            $year -= 1;
            $week = YearHelper::weeksInIsoYear($year);
        } elseif ($week > $weeks) {
            $year += 1;
            $week = 1;
        }

        return $this->dateArray = [$year + self::Y400_YEARS * $cycle, $week, $weekDay];
    }

    public function getYear(): int
    {
        return $this->getDateArray()[0];
    }

    public function getWeek(): int
    {
        return $this->getDateArray()[1];
    }

    public function getWeekDay(): WeekDay
    {
        return WeekDay::from($this->getDateArray()[2]);
    }

    public function getWeekDayNumber(): int
    {
        return $this->getDateArray()[2];
    }

    // string conversion

    public function toString(): string
    {
        $ywd = $this->getDateArray();
        return sprintf("%d-W%02d-%d", $ywd[0], $ywd[1], $ywd[2]);
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
