<?php

/**
 * @copyright 2024 Anton Smirnov
 * @license MIT https://spdx.org/licenses/MIT.html
 */

declare(strict_types=1);

namespace Arokettu\Date\Traits;

use Arokettu\Date\DateInterface;

trait BaseTrait
{
    // Julian day

    public function __construct(
        public readonly int $julianDay,
    ) {
    }

    public static function fromJulianDay(int $julianDay): self
    {
        return new self($julianDay);
    }

    public static function fromDateInterface(DateInterface $date): self
    {
        return $date instanceof self ? $date : new self($date->julianDay);
    }

    public function getJulianDay(): int
    {
        return $this->julianDay;
    }

    // day arithmetic

    public function addDays(int $days): self
    {
        return new self($this->julianDay + $days);
    }

    public function subDays(int $days): self
    {
        return new self($this->julianDay - $days);
    }

    public function sub(DateInterface $date): int
    {
        return $this->julianDay - $date->julianDay;
    }

    public function compare(DateInterface $date): int
    {
        return $this->julianDay <=> $date->julianDay;
    }
}
