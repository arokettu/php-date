<?php

/**
 * @copyright 2024 Anton Smirnov
 * @license MIT https://spdx.org/licenses/MIT.html
 */

declare(strict_types=1);

namespace Arokettu\Date;

use DateTimeInterface;
use DateTimeZone;

/**
 * Gregorian calendar and DateTime interoperability
 * @deprecated Use methods directly on the Date class
 */
final readonly class Calendar
{
    public static function create(int $y, Month|int $m, int $d): Date
    {
        return Date::create($y, $m, $d);
    }

    public static function parse(string $string): Date
    {
        return Date::parse($string);
    }

    public static function fromString(string $string): Date
    {
        return Date::fromString($string);
    }

    public static function fromDateTime(DateTimeInterface $dateTime): Date
    {
        return Date::fromDateTime($dateTime);
    }

    public static function parseDateTimeString(string $string, DateTimeZone|null $timeZone = null): Date
    {
        return Date::parseDateTimeString($string, $timeZone);
    }

    public static function fromTimestamp(int|float $timestamp, DateTimeZone|null $timeZone = null): Date
    {
        return Date::fromTimestamp($timestamp, $timeZone);
    }
}
