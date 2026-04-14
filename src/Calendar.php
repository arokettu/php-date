<?php

/**
 * @copyright 2024 Anton Smirnov
 * @license MIT https://spdx.org/licenses/MIT.html
 */

declare(strict_types=1);

namespace Arokettu\Date;

use DateTimeImmutable;
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

    // DateTime conversion

    public static function fromDateTime(DateTimeInterface $dateTime): Date
    {
        $y = \intval($dateTime->format('Y'));
        $m = \intval($dateTime->format('m'));
        $d = \intval($dateTime->format('d'));

        return Date::create($y, $m, $d);
    }

    public static function parseDateTimeString(string $string, DateTimeZone|null $timeZone = null): Date
    {
        return self::fromDateTime(new DateTimeImmutable($string, $timeZone));
    }

    public static function fromTimestamp(int|float $timestamp, DateTimeZone|null $timeZone = null): Date
    {
        if (PHP_VERSION_ID >= 80400) {
            // @codeCoverageIgnoreStart
            $dt = DateTimeImmutable::createFromTimestamp($timestamp);
            // @codeCoverageIgnoreEnd
        } elseif (\is_int($timestamp)) {
            $dt = DateTimeImmutable::createFromFormat('U', (string)$timestamp);
        } else {
            $dt = DateTimeImmutable::createFromFormat('U', \sprintf('%.0F', $timestamp));
        }

        if ($timeZone) {
            $dt = $dt->setTimezone($timeZone);
        }

        return self::fromDateTime($dt);
    }
}
