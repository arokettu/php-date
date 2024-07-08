<?php

declare(strict_types=1);

namespace Arokettu\Date;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use DomainException;

/**
 * Gregorian calendar and DateTime interoperability
 */
final readonly class Calendar
{
    // ymd factory

    private static function fromGregorianRaw(int $y, int $m, int $d): Date
    {
        // normalize to 0..400 years (146097 days)
        if ($y >= 0) {
            $c1 = intdiv($y, 400);
            $c2 = 0;
        } else {
            // this insane code here is to avoid int overflow on PHP_INT_MIN
            // because simple logic with $c1 * 146097 may overflow, so we split one correction with two
            // that's guaranteed to be in range as long as the final result is in range
            $c1 = intdiv($y, 400) - 1;
            $c2 = intdiv($c1, 2);
            $c1 -= $c2;
        }
        $y -= ($c1 + $c2) * 400;

        // https://en.wikipedia.org/wiki/Julian_day#Converting_Gregorian_calendar_date_to_Julian_Day_Number
        $monthCorrection = intdiv($m - 14, 12);
        $julianDay =
            intdiv(1461 * ($y + 4800 + $monthCorrection), 4) +
            intdiv(367 * ($m - 2 - 12 * $monthCorrection), 12) -
            intdiv(3 * (intdiv($y + 4900 + $monthCorrection, 100)), 4) +
            $d - 32075;
        $julianDay += $c1 * 146097;
        $julianDay += $c2 * 146097;

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

        $days = $mo->gregorianDays($y);

        if ($d < 1 || $d > $days) {
            throw new DomainException("For year $y month $mi, day must be in range 1-$days");
        }

        return self::fromGregorianRaw($y, $mi, $d);
    }

    public static function parse(string $string): Date
    {
        return self::fromString($string);
    }

    public static function fromString(string $string): Date
    {
        if (!preg_match('/^(-?\d+)-(\d+)-(\d+)$/', $string, $matches)) {
            throw new DomainException('Unable to parse the date string: ' . $string);
        }

        [/* $_ */, $y, $m, $d] = $matches;

        return self::create(\intval($y), \intval($m), \intval($d));
    }

    // DateTime conversion

    public static function fromDateTime(DateTimeInterface $dateTime): Date
    {
        $y = \intval($dateTime->format('Y'));
        $m = \intval($dateTime->format('m'));
        $d = \intval($dateTime->format('d'));

        return self::fromGregorianRaw($y, $m, $d);
    }

    public static function parseDateTimeString(string $string, DateTimeZone|null $timeZone = null): Date
    {
        return self::fromDateTime(new DateTimeImmutable($string, $timeZone));
    }
}
