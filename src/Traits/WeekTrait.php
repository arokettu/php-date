<?php

/**
 * @copyright 2024 Anton Smirnov
 * @license MIT https://spdx.org/licenses/MIT.html
 */

declare(strict_types=1);

namespace Arokettu\Date\Traits;

use Arokettu\Date\Helpers\MathHelper;
use Arokettu\Date\WeekDay;

/**
 * @internal
 */
trait WeekTrait
{
    public readonly int $julianDay;

    abstract private function copyWith(int $julianDay): self;

    public function getWeekDay(): WeekDay
    {
        return WeekDay::from($this->getWeekDayNumber());
    }

    public function getWeekDayNumber(): int
    {
        return MathHelper::mod($this->julianDay, 7) + 1;
    }

    public function addWeeks(int $weeks): self
    {
        return $this->copyWith($this->julianDay + $weeks * 7);
    }

    public function subWeeks(int $weeks): self
    {
        return $this->copyWith($this->julianDay - $weeks * 7);
    }
}
