<?php

/**
 * @copyright 2024 Anton Smirnov
 * @license MIT https://spdx.org/licenses/MIT.html
 */

declare(strict_types=1);

namespace Arokettu\Date\Traits;

use Arokettu\Date\Date;
use Arokettu\Date\JulianDate;
use Arokettu\Date\MilankovicDate;

/**
 * @internal
 */
trait DeprecatedTrait
{
    /**
     * @deprecated
     * @see Date::fromJulianDay()
     */
    public static function createFromJulianDay(int $julianDay): Date
    {
        return self::fromJulianDay($julianDay);
    }

    /**
     * @deprecated
     * @see Date:addDays()
     */
    public function add(int $days): Date
    {
        return $this->addDays($days);
    }

    /**
     * @deprecated
     * @see Date::toJulian()
     */
    public function julian(): JulianDate
    {
        return $this->toJulian();
    }

    /**
     * @deprecated
     * @see Date::toMilankovic()
     */
    public function milankovic(): MilankovicDate
    {
        return $this->toMilankovic();
    }
}
