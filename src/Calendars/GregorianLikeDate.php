<?php

declare(strict_types=1);

namespace Arokettu\Date\Calendars;

use Arokettu\Date\Month;

/**
 * Calendar with Years, Roman Months and Days
 */
trait GregorianLikeDate
{
    private readonly array $dateArray;

    abstract public function getDateArray(): array; // must return [y, m, d]

    public function __construct(
        public readonly int $julianDay,
    ) {
    }

    public function getYear(): int
    {
        return $this->getDateArray()[0];
    }

    public function getMonth(): Month
    {
        return Month::from($this->getDateArray()[1]);
    }

    public function getMonthNumber(): int
    {
        return $this->getDateArray()[1];
    }

    public function getDay(): int
    {
        return $this->getDateArray()[2];
    }

    // string conversion

    public function toString(): string
    {
        $ymd = $this->getDateArray();
        return sprintf("%d-%02d-%02d", $ymd[0], $ymd[1], $ymd[2]);
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
