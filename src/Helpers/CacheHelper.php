<?php

/**
 * @copyright 2024 Anton Smirnov
 * @license MIT https://spdx.org/licenses/MIT.html
 */

declare(strict_types=1);

namespace Arokettu\Date\Helpers;

use Arokettu\Date\Calendars;
use Arokettu\Date\Date;
use WeakMap;

/**
 * @internal
 */
final class CacheHelper
{
    /** @var WeakMap<Date, array<int, Calendars\CivilDate>> */
    public static WeakMap $civilDateObject;
}
