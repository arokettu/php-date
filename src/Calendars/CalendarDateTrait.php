<?php

declare(strict_types=1);

namespace Arokettu\Date\Calendars;

use Arokettu\Date\Date;
use Arokettu\Date\WeekDay;

trait CalendarDateTrait
{
    abstract public function toString(): string;

    public function __construct(
        public readonly Date $date,
    ) {}

    // getter for the interface

    public function getDate(): Date
    {
        return $this->date;
    }

    // Julian day

    public function getJulianDay(): int
    {
        return $this->date->julianDay;
    }

    public static function createFromJulianDay(int $julianDay): self
    {
        return new self(new Date($julianDay));
    }

    // weekday

    public function getWeekDay(): WeekDay
    {
        return $this->date->getWeekDay();
    }

    public function getWeekDayNumber(): int
    {
        return $this->date->getWeekDayNumber();
    }

    // arithmetic

    public function add(int $days): self
    {
        return new self($this->date->add($days));
    }

    public function subDays(int $days): self
    {
        return new self($this->date->subDays($days));
    }

    public function sub(Date|CalendarDateInterface $date): int
    {
        return $this->date->sub($date);
    }

    // magic

    public function __serialize(): array
    {
        return [$this->date];
    }

    public function __unserialize(array $data): void
    {
        [$this->date] = $data;
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public function __debugInfo(): array
    {
        return [
            'calendarDate' => $this->toString(),
            'baseDate' => $this->date->__debugInfo(),
        ];
    }
}
