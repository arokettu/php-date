<?php

declare(strict_types=1);

namespace Arokettu\Date;

use Arokettu\Date\Helpers\YearHelper;
use DomainException;

final readonly class IsoWeekCalendar
{
    private const Y400_DAYS = 146097;
    private const Y400_YEARS = 400;
    private const BASE_DAY = 1721060; // 0-1-1 Gregorian

    private static function fromIsoWeekRaw(int $y, int $w, int $d): Date
    {
        // normalize to 1..400 years (146097 days)
        if ($y >= 0) {
            $c1 = intdiv($y, self::Y400_YEARS);
            $c2 = 0;
        } else {
            // this insane code here is to avoid int overflow on PHP_INT_MIN
            // because simple logic with $c1 * 328718 may overflow, so we split one correction with two
            // that's guaranteed to be in range as long as the final result is in range
            $c1 = intdiv($y, self::Y400_YEARS) - 1;
            $c2 = intdiv($c1, 2);
            $c1 -= $c2;
        }
        $y -= ($c1 + $c2) * self::Y400_YEARS;

        $jan4WeekDay = YearHelper::wdIsoYearJan4($y);
        $dayOfYear = $w * 7 + $d - $jan4WeekDay - 4;

        $yearDays = YearHelper::isGregorianLeap($y) ? 366 : 365;

        if ($dayOfYear < 1) {
            $y -= 1;
            $yearDays = YearHelper::isGregorianLeap($y) ? 366 : 365;
            $dayOfYear += $yearDays;
        } elseif ($dayOfYear > $yearDays) {
            $y += 1;
            $dayOfYear -= $yearDays;
        }

        $julianDay = 365 * $y
            + intdiv($y + 3, 4)
            - intdiv($y + 99, 100)
            + intdiv($y + 399, 400)
            + $dayOfYear + self::BASE_DAY;

        // apply back correction
        $julianDay += $c1 * self::Y400_DAYS;
        $julianDay += $c2 * self::Y400_DAYS;

        if (\is_integer($julianDay) === false) {
            throw new DomainException('Date value overflow');
        }

        return new Date($julianDay);
    }

    public static function create(int $y, int $w, WeekDay|int $d): Date
    {
        if ($d instanceof WeekDay) {
            $di = $d->value;
        } else {
            WeekDay::tryFrom($d) ??
                throw new DomainException('Day must be an instance of WeekDay or an integer 1-7');
            $di = $d;
        }

        $weeks = YearHelper::weeksInIsoYear($y);

        if ($w < 1 || $w > $weeks) {
            throw new DomainException("For year $y, week must be in range 1-$weeks");
        }

        return self::fromIsoWeekRaw($y, $w, $di);
    }

    public static function parse(string $string): Date
    {
        return self::fromString($string);
    }

    public static function fromString(string $string): Date
    {
        if (
            !preg_match('/^(-?\d+)-W?(\d+)-(\d+)$/i', $string, $matches) &&
            !preg_match('/^(-?\d+)W(\d{2})(\d)$/i', $string, $matches)
        ) {
            throw new DomainException('Unable to parse the date string: ' . $string);
        }

        [/* $_ */, $y, $m, $d] = $matches;

        return self::create(\intval($y), \intval($m), \intval($d));
    }
}
