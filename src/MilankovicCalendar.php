<?php

/**
 * @copyright 2024 Anton Smirnov
 * @license MIT https://spdx.org/licenses/MIT.html
 */

declare(strict_types=1);

namespace Arokettu\Date;

/**
 * @deprecated Use methods directly on the MilankovicDate class
 */
final readonly class MilankovicCalendar
{
    public static function create(int $y, Month|int $m, int $d): Date
    {
        return MilankovicDate::create($y, $m, $d)->toGregorian();
    }

    public static function parse(string $string): Date
    {
        return MilankovicDate::parse($string)->toGregorian();
    }

    public static function fromString(string $string): Date
    {
        return MilankovicDate::fromString($string)->toGregorian();
    }
}
