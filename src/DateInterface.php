<?php

/**
 * @copyright 2024 Anton Smirnov
 * @license MIT https://spdx.org/licenses/MIT.html
 */

declare(strict_types=1);

namespace Arokettu\Date;

use Stringable;

/**
 * @property-read int $julianDay
 */
interface DateInterface extends Stringable
{
    public function getJulianDay(): int;

    public function compare(DateInterface $date): int;

    public function toGregorian(): Date;
}
