<?php

declare(strict_types=1);

namespace Arokettu\Date\Helpers;

use WeakMap;

/**
 * @internal
 */
final class CacheHelper
{
    // gregorian y-m-d
    public static WeakMap $dateArray;

    // calendars
    public static WeakMap $isoWeekDateObject;
    public static WeakMap $julianDateObject;
    public static WeakMap $milankovicDateObject;
}
