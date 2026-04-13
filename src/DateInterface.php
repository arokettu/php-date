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
    public static function fromJulianDay(int $julianDay): self;
    public static function fromDateInterface(DateInterface $date): self;

    public function getJulianDay(): int;

    public function toGregorian(): Date;
}
