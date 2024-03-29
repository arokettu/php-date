<?php

declare(strict_types=1);

namespace Arokettu\Date\Calendars;

use Arokettu\Date\Date;
use Arokettu\Date\Helpers\CacheHelper;
use Arokettu\Date\Month;
use DomainException;
use WeakMap;

final readonly class JulianCalendarDate implements CalendarDateInterface
{
    use CalendarDateTrait;

    public function getDateArray(): array
    {
        $cache = CacheHelper::$julianDateArray ??= new WeakMap();
        if (isset($cache[$this])) {
            return $cache[$this];
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

        return $cache[$this] = [$y + $c * 700, $m, $d];
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

    // ymd factory

    private static function fromJulianRaw(int $y, int $m, int $d): self
    {
        // normalize to 0..700 years (255675 days)
        if ($y >= 0) {
            $c1 = intdiv($y, 700);
            $c2 = 0;
        } else {
            // this insane code here is to avoid int overflow on PHP_INT_MIN
            // because simple logic with $c1 * 255675 may overflow, so we split one correction with two
            // that's guaranteed to be in range as long as the final result is in range
            $c1 = intdiv($y, 700) - 1;
            $c2 = intdiv($c1, 2);
            $c1 -= $c2;
        }
        $y -= ($c1 + $c2) * 700;

        // https://en.wikipedia.org/wiki/Julian_day#Converting_Gregorian_calendar_date_to_Julian_Day_Number
        $julianDay =
            367 * $y -
            intdiv(7 * ($y + 5001 + intdiv($m - 9, 7)), 4) +
            intdiv(275 * $m, 9) +
            $d + 1729777;
        $julianDay += $c1 * 255675;
        $julianDay += $c2 * 255675;

        if (\is_integer($julianDay) === false) {
            throw new DomainException('Date value overflow');
        }

        return new self(new Date($julianDay));
    }

    public static function create(int $y, Month|int $m, int $d): self
    {
        if ($m instanceof Month) {
            $mo = $m;
            $mi = $m->value;
        } else {
            $mo = Month::tryFrom($m) ??
                throw new DomainException('Month must be an instance of Month or an integer 1-12');
            $mi = $m;
        }

        $days = $mo->julianDays($y);

        if ($d < 1 || $d > $days) {
            throw new DomainException("For year $y month $mi, day must be in range 1-$days");
        }

        return self::fromJulianRaw($y, $mi, $d);
    }

    public static function parse(string $string): self
    {
        return self::fromString($string);
    }

    public static function fromString(string $string): self
    {
        if (!preg_match('/(-?\d+)-(\d+)-(\d+)/', $string, $matches)) {
            throw new DomainException('Unable to parse the date string: ' . $string);
        }

        [/* $_ */, $y, $m, $d] = $matches;

        return self::create(\intval($y), \intval($m), \intval($d));
    }
}
