<?php

declare(strict_types=1);

namespace Arokettu\Date\Helpers;

use WeakMap;

/**
 * @internal
 */
final class CacheHelper
{
    // gregorian
    public static WeakMap $dateArray;

    // julian
    public static WeakMap $julianDateObject;
    public static WeakMap $julianDateArray;
}
