<?php

/**
 * @copyright 2024 Anton Smirnov
 * @license MIT https://spdx.org/licenses/MIT.html
 */

declare(strict_types=1);

namespace Arokettu\Date;

/**
 * @deprecated
 */
final readonly class JulianCalendar
{
    public static function create(int $y, Month|int $m, int $d): Date
    {
        return JulianDate::create($y, $m, $d)->toGregorian();
    }

    public static function parse(string $string): Date
    {
        return JulianDate::parse($string)->toGregorian();
    }

    public static function fromString(string $string): Date
    {
        return JulianDate::fromString($string)->toGregorian();
    }
}
