<?php

/**
 * @copyright 2024 Anton Smirnov
 * @license MIT https://spdx.org/licenses/MIT.html
 */

declare(strict_types=1);

namespace Arokettu\Date;

use Arokettu\Date\Helpers\MathHelper;
use Closure;

final readonly class Easter
{
    public static function gregorian(int $year): Date
    {
        // Gauss formula
        $a = $year % 19;
        $b = $year % 4;
        $c = $year % 7;

        $k = intdiv($year, 100);
        $p = intdiv(13 + 8 * $k, 25);
        $q = intdiv($k, 4);

        $m = MathHelper::mod(15 - $p + $k - $q, 30);
        $n = MathHelper::mod(4 + $k - $q, 7);

        $d = MathHelper::mod(19 * $a + $m, 30);
        $e = MathHelper::mod(2 * $b + 4 * $c + 6 * $d + $n, 7);
        $easter = $d + $e - 9;

        if ($easter === 25 && $d === 28 && $e === 6 && $a > 10) {
            $easter = 18;
        } elseif ($easter === 26 && $d === 29 && $e === 6) {
            $easter = 19;
        }

        // skip checks to "fall into" March if needed
        return Closure::bind(
            static fn ($easter) => Calendar::fromGregorianRaw($year, 4, $easter),
            null,
            Calendar::class,
        )($easter);
    }

    public static function julian(int $year): Date
    {
        // Gauss formula
        $a = $year % 19;
        $b = $year % 4;
        $c = $year % 7;

        $d = MathHelper::mod(19 * $a + 15, 30);
        $e = MathHelper::mod(2 * $b + 4 * $c + 6 * $d + 6, 7);
        $easter = $d + $e - 9;

        // skip checks to "fall into" March if needed
        return Closure::bind(
            static fn ($easter) => JulianCalendar::fromJulianRaw($year, 4, $easter),
            null,
            JulianCalendar::class,
        )($easter);
    }
}
