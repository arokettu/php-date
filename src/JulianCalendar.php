<?php

declare(strict_types=1);

namespace Arokettu\Date;

use DomainException;
use RangeException;
use UnexpectedValueException;

final readonly class JulianCalendar
{
    // ymd factory

    private static function fromJulianRaw(int $y, int $m, int $d): Date
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
            throw new RangeException('Date value overflow');
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

        $days = $mo->julianDays($y);

        if ($d < 1 || $d > $days) {
            throw new DomainException("For year $y month $mi, day must be in range 1-$days");
        }

        return self::fromJulianRaw($y, $mi, $d);
    }

    public static function parse(string $string): Date
    {
        return self::fromString($string);
    }

    public static function fromString(string $string): Date
    {
        if (!preg_match('/^(-?\d+)-(\d+)-(\d+)$/', $string, $matches)) {
            throw new UnexpectedValueException(\sprintf('Unable to parse the date string: "%s"', $string));
        }

        [/* $_ */, $y, $m, $d] = $matches;

        try {
            return self::create(\intval($y), \intval($m), \intval($d));
        } catch (DomainException $e) {
            throw new UnexpectedValueException(
                \sprintf('Unable to parse the date string: "%s". %s', $string, $e->getMessage()),
                previous: $e,
            );
        }
    }
}
