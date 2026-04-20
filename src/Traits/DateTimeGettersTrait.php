<?php

/**
 * @copyright 2024 Anton Smirnov
 * @license MIT https://spdx.org/licenses/MIT.html
 */

declare(strict_types=1);

namespace Arokettu\Date\Traits;

use DateTimeImmutable;
use DateTimeZone;

/**
 * @internal
 */
trait DateTimeGettersTrait
{
    public function toDateTime(DateTimeZone|null $timeZone = null): DateTimeImmutable
    {
        $ymd = $this->getDateArray();
        return (new DateTimeImmutable('today', $timeZone))->setDate($ymd[0], $ymd[1], $ymd[2]);
    }

    public function formatDateTime(string $format, DateTimeZone|null $timeZone = null): string
    {
        return $this->toDateTime($timeZone)->format($format);
    }
}
