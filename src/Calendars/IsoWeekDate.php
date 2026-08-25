<?php

/**
 * @copyright 2024 Anton Smirnov
 * @license MIT https://spdx.org/licenses/MIT.html
 */

declare(strict_types=1);

namespace Arokettu\Date\Calendars;

use Arokettu\Date\IsoWeekDate as NewIsoWeekDate;

class_alias(NewIsoWeekDate::class, IsoWeekDate::class);

if (false) {
    /**
     * @deprecated
     */
    final readonly class IsoWeekDate extends NewIsoWeekDate
    {
    }
}
