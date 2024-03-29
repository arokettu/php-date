<?php

declare(strict_types=1);

namespace Arokettu\Date\Calendars;

use Arokettu\Date\Date;
use Arokettu\Date\WeekDay;

interface CalendarDateInterface
{
    public function getDate(): Date;
    public function getJulianDay(): int;

    public function getWeekDay(): WeekDay;
    public function getWeekDayNumber(): int;

    public function toString(): string;

    public function add(int $days): self;
    public function subDays(int $days): self;
    public function sub(Date|CalendarDateInterface $date): int;
}
