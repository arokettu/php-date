<?php

declare(strict_types=1);

namespace Arokettu\Date;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Stringable;

final readonly class Date implements Stringable
{
    public function __construct(
        public int $julianDay,
    ) {
    }

    // various getters

    public function getJulianDay(): int
    {
        return $this->julianDay;
    }

    public function getWeekDay(): WeekDay
    {
        $wd = $this->julianDay % 7 + 1;
        return WeekDay::from($wd);
    }

    public function getDateArray(): array
    {
        // https://en.wikipedia.org/wiki/Julian_day#Julian_or_Gregorian_calendar_from_Julian_day_number
        $f = $this->julianDay + 1401 + intdiv(intdiv(4 * $this->julianDay + 274277, 146097) * 3, 4) - 38;
        $e = 4 * $f + 3;
        $g = intdiv($e % 1461, 4);
        $h = 5 * $g + 2;

        $d = intdiv($h % 153, 5) + 1;
        $m = (intdiv($h, 153) + 2) % 12 + 1;
        $y = intdiv($e, 1461) - 4716 + intdiv(12 + 2 - $m, 12);

        return [$y, $m, $d];
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

    // DateTime conversion

    public static function fromDateTime(DateTimeInterface $dateTime): self
    {
        // https://en.wikipedia.org/wiki/Julian_day#Converting_Gregorian_calendar_date_to_Julian_Day_Number
        $y = \intval($dateTime->format('Y'));
        $m = \intval($dateTime->format('m'));
        $d = \intval($dateTime->format('d'));

        $monthCorrection = intdiv($m - 14, 12);
        $julianDay =
            intdiv(1461 * ($y + 4800 + $monthCorrection), 4) +
            intdiv(367 * ($m - 2 - 12 * $monthCorrection), 12) -
            intdiv(3 * (intdiv($y + 4900 + $monthCorrection, 100)), 4) +
            $d - 32075;

        return new self($julianDay);
    }

    public function toDateTime(?DateTimeZone $timeZone = null): DateTimeImmutable
    {
        $ymd = $this->getDateArray();
        return (new DateTimeImmutable('today', $timeZone))->setDate($ymd[0], $ymd[1], $ymd[2]);
    }

    // magic

    public function __serialize(): array
    {
        return [$this->julianDay];
    }

    public function __unserialize(array $data): void
    {
        [$this->julianDay] = $data;
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
