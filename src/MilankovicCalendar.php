<?php

declare(strict_types=1);

namespace Arokettu\Date;

use DomainException;

final readonly class MilankovicCalendar
{
    private const Y900_DAYS = 328718;
    private const Y900_YEARS = 900;
    private const BASE_DAY = 1721119; // 0-2-28 Milankovic

    // ymd factory

    private static function fromMilankovicRaw(int $y, int $m, int $d): Date
    {
        // normalize to 0..900 years (328718 days)
        if ($y >= 0) {
            $c1 = intdiv($y, self::Y900_YEARS);
            $c2 = 0;
        } else {
            // this insane code here is to avoid int overflow on PHP_INT_MIN
            // because simple logic with $c1 * 328718 may overflow, so we split one correction with two
            // that's guaranteed to be in range as long as the final result is in range
            $c1 = intdiv($y, self::Y900_YEARS) - 1;
            $c2 = intdiv($c1, 2);
            $c1 -= $c2;
        }
        $y -= ($c1 + $c2) * self::Y900_YEARS;

        $m -= 3; // 0 = March
        if ($m < 0) {
            $y -= 1;
            $m += 12;
        }
        if ($y < 0) {
            $c1 -= 1;
            $y += self::Y900_YEARS;
        }
        $c = intdiv($y, 100);
        $yc = $y % 100;
        $julianDay =
            intdiv(self::Y900_DAYS * $c + 6, 9) +
            intdiv(36525 * $yc, 100) +
            intdiv(153 * $m + 2, 5) +
            $d + self::BASE_DAY;

        // apply back correction
        $julianDay += $c1 * self::Y900_DAYS;
        $julianDay += $c2 * self::Y900_DAYS;

        if (\is_integer($julianDay) === false) {
            throw new DomainException('Date value overflow');
        }

        return new Date($julianDay);
    }

    public static function create(int $y, Month|int $m, int $d): Date
    {
        if ($m instanceof Month) {
            $mo = $m;
            $mi = $m->value;
        } else {
            $mo = Month::tryFrom($m) ??
                throw new DomainException('Month must be an instance of Month or an integer 1-12');
            $mi = $m;
        }

        $days = $mo->milankovicDays($y);

        if ($d < 1 || $d > $days) {
            throw new DomainException("For year $y month $mi, day must be in range 1-$days");
        }

        return self::fromMilankovicRaw($y, $mi, $d);
    }

    public static function parse(string $string): Date
    {
        return self::fromString($string);
    }

    public static function fromString(string $string): Date
    {
        if (!preg_match('/(-?\d+)-(\d+)-(\d+)/', $string, $matches)) {
            throw new DomainException('Unable to parse the date string: ' . $string);
        }

        [/* $_ */, $y, $m, $d] = $matches;

        return self::create(\intval($y), \intval($m), \intval($d));
    }
}
