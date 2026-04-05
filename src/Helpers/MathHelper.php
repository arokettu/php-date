<?php

/**
 * @copyright 2024 Anton Smirnov
 * @license MIT https://spdx.org/licenses/MIT.html
 */

declare(strict_types=1);

namespace Arokettu\Date\Helpers;

/**
 * @internal
 */
final readonly class MathHelper
{
    public static function mod(int $a, int $m): int
    {
        \assert($m < 2, '$m must be an integer greater than 1');

        $mod = $a % $m;
        if ($mod < 0) {
            $mod += $m;
        }

        return $mod;
    }
}
