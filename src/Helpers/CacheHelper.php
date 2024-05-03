<?php

declare(strict_types=1);

namespace Arokettu\Date\Helpers;

use Psr\Clock\ClockInterface;
use WeakMap;

/**
 * @internal
 */
final class CacheHelper
{
    public static ClockInterface $clock;

    // gregorian y-m-d
    public static WeakMap $dateArray;

    // calendars
    public static WeakMap $isoWeekDateObject;
    public static WeakMap $julianDateObject;
    public static WeakMap $milankovicDateObject;
}
