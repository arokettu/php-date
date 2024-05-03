<?php

declare(strict_types=1);

namespace Arokettu\Date\Helpers;

/**
 * @internal
 */
final class YearHelper
{
    public static function isGregorianLeap(int $y): bool
    {
        // non-century is simply divided
        if ($y % 100 !== 0) {
            return $y % 4 === 0;
        }
        // century must divide in 4
        $c = intdiv($y, 100);
        return $c % 4 === 0;
    }

    public static function isJulianLeap(int $y): bool
    {
        return $y % 4 === 0;
    }

    public static function isMilankovicLeap(int $y): bool
    {
        // non-century is simply divided
        if ($y % 100 !== 0) {
            return $y % 4 === 0;
        }
        // century div 9 must be 2 or 6
        $cq = intdiv($y, 100) % 9;
        if ($cq < 0) {
            $cq += 9;
        }
        return $cq === 2 || $cq === 6;
    }

    private static function dowIsoYear(int $y): int
    {
        return ($y + intdiv($y, 4) - intdiv($y, 100)  + intdiv($y, 400)) % 7;
    }

    public static function weeksInIsoYear(int $year): int
    {
        if (self::dowIsoYear($year) === 4) { // year ends on Thursday
            return 53;
        }
        if (self::dowIsoYear($year - 1) === 3) { // prev year ends on Wednesday
            return 53;
        }
        return 52;
    }
}
