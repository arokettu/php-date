<?php

declare(strict_types=1);

namespace Arokettu\Date\Helpers;

/**
 * @internal
 */
final class YearHelper
{
    public static function isLeap(int $y): bool
    {
        // gregorian leap year
        if ($y % 100 !== 0) {
            return $y % 4 === 0;
        }
        $c = intdiv($y, 100);
        return $c % 4 === 0;
    }

    public static function isJulianLeap(int $y): bool
    {
        // julian leap year
        return $y % 4 === 0;
    }
}
