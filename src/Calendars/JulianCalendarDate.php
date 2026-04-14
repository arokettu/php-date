<?php

/**
 * @copyright 2024 Anton Smirnov
 * @license MIT https://spdx.org/licenses/MIT.html
 */

declare(strict_types=1);

namespace Arokettu\Date\Calendars;

use Arokettu\Date\JulianDate;

class_alias(JulianDate::class, JulianCalendarDate::class);

if (false) {
    /**
     * @deprecated
     */
    final readonly class JulianCalendarDate extends JulianDate
    {

    }
}
