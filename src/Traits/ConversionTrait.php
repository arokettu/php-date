<?php

/**
 * @copyright 2024 Anton Smirnov
 * @license MIT https://spdx.org/licenses/MIT.html
 */

declare(strict_types=1);

namespace Arokettu\Date\Traits;

use Arokettu\Date\Date;
use Arokettu\Date\JulianDate;

/**
 * @internal
 */
trait ConversionTrait
{
    public readonly int $julianDay;

    public function toGregorian(): Date
    {
        return new Date($this->julianDay);
    }

    public function toJulian(): JulianDate
    {
        return new JulianDate($this->julianDay);
    }
}
