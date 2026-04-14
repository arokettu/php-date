<?php

/**
 * @copyright 2024 Anton Smirnov
 * @license MIT https://spdx.org/licenses/MIT.html
 */

declare(strict_types=1);

namespace Arokettu\Date\Calendars;

use Arokettu\Date\Traits\GregorianGettersTrait;

trait GregorianLikeDate
{
    use GregorianGettersTrait;

    private readonly array $dateArray;

    public function __construct(
        public readonly int $julianDay,
    ) {
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
