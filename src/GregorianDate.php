<?php

/**
 * @copyright 2024 Anton Smirnov
 * @license MIT https://spdx.org/licenses/MIT.html
 */

declare(strict_types=1);

namespace Arokettu\Date;

class_alias(Date::class, GregorianDate::class);

if (false) {
    /**
     * Just an alias of Date
     */
    final readonly class GregorianDate extends Date
    {
    }
}
