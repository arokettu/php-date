<?php

/**
 * @copyright 2024 Anton Smirnov
 * @license MIT https://spdx.org/licenses/MIT.html
 */

declare(strict_types=1);

namespace Arokettu\Date;

/**
 * @deprecated Use methods directly on the IsoWeekDate class
 */
final readonly class IsoWeekCalendar
{
    public static function create(int $y, int $w, WeekDay|int $d): Date
    {
        return IsoWeekDate::create($y, $w, $d)->toGregorian();
    }

    public static function parse(string $string): Date
    {
        return IsoWeekDate::parse($string)->toGregorian();
    }

    public static function fromString(string $string): Date
    {
        return IsoWeekDate::fromString($string)->toGregorian();
    }
}
