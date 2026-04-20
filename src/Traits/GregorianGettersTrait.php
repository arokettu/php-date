<?php

/**
 * @copyright 2024 Anton Smirnov
 * @license MIT https://spdx.org/licenses/MIT.html
 */

declare(strict_types=1);

namespace Arokettu\Date\Traits;

use Arokettu\Date\Month;

/**
 * Calendar with Years, Roman Months and Days
 * @internal
 */
trait GregorianGettersTrait
{
    private readonly array $dateArray;

    public function getDateArray(): array
    {
        return $this->dateArray;
    }

    public function getYear(): int
    {
        return $this->dateArray[0];
    }

    public function getMonth(): Month
    {
        return Month::from($this->dateArray[1]);
    }

    public function getMonthNumber(): int
    {
        return $this->dateArray[1];
    }

    public function getDay(): int
    {
        return $this->dateArray[2];
    }

    // string conversion

    public function toString(): string
    {
        return \sprintf('%d-%02d-%02d', $this->dateArray[0], $this->dateArray[1], $this->dateArray[2]);
    }
}
