<?php

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
    // gregorian y-m-d
    public static WeakMap $dateArray;

    // calendars
    /** @var WeakMap<Date, Calendars\IsoWeekDate> */
    public static WeakMap $isoWeekDateObject;
    /** @var WeakMap<Date, Calendars\JulianCalendarDate> */
    public static WeakMap $julianDateObject;
    /** @var WeakMap<Date, Calendars\MilankovicDate> */
    public static WeakMap $milankovicDateObject;
}
