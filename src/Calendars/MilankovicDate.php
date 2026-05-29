<?php

/**
 * @copyright 2024 Anton Smirnov
 * @license MIT https://spdx.org/licenses/MIT.html
 */

declare(strict_types=1);

namespace Arokettu\Date\Calendars;

use Arokettu\Date\MilankovicDate as NewMilankovicDate;

class_alias(NewMilankovicDate::class, MilankovicDate::class);

if (false) {
    /**
     * @deprecated
     */
    final readonly class MilankovicDate extends NewMilankovicDate
    {
    }
}
