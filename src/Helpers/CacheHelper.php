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

    // gregorian
    public static WeakMap $dateArray;

    // julian
    public static WeakMap $julianDateObject;
}
