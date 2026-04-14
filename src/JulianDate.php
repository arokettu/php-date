<?php

/**
 * @copyright 2024 Anton Smirnov
 * @license MIT https://spdx.org/licenses/MIT.html
 */

declare(strict_types=1);

namespace Arokettu\Date;

use RangeException;

final readonly class JulianDate implements DateInterface
{
    use Traits\BaseTrait;
    use Traits\ConversionTrait;
    use Traits\WeekTrait;
    use Traits\GregorianGettersTrait;
    use Traits\GregorianCreationTrait;
    use Traits\DateTimeGettersTrait;
    use Traits\DateTimeCreationTrait;

    public function __construct(
        public int $julianDay,
    ) {
        $this->init();
    }

    private function copyWith(int $julianDay): self
    {
        return new self($julianDay);
    }

    public function toJulian(): JulianDate
    {
        return $this; // optimize
    }

    // date to julian

    private static function fromRaw(int $y, int $m, int $d): self
    {
        // normalize to 0..700 years (255675 days)
        if ($y >= 0) {
            $c1 = intdiv($y, 700);
            $c2 = 0;
        } else {
            // this insane code here is to avoid int overflow on PHP_INT_MIN
            // because simple logic with $c1 * 255675 may overflow, so we split one correction with two
            // that's guaranteed to be in range as long as the final result is in range
            $c1 = intdiv($y, 700) - 1;
            $c2 = intdiv($c1, 2);
            $c1 -= $c2;
        }
        $y -= ($c1 + $c2) * 700;

        // https://en.wikipedia.org/wiki/Julian_day#Converting_Gregorian_calendar_date_to_Julian_Day_Number
        $julianDay =
            367 * $y -
            intdiv(7 * ($y + 5001 + intdiv($m - 9, 7)), 4) +
            intdiv(275 * $m, 9) +
            $d + 1729777;
        $julianDay += $c1 * 255675;
        $julianDay += $c2 * 255675;

        if (\is_integer($julianDay) === false) {
            throw new RangeException('Date value overflow');
        }

        return new JulianDate($julianDay);
    }

    private static function getMonthDays(int $year, Month $month): int
    {
        return $month->julianDays($year);
    }

    // julian to date

    public function init(): void
    {
        if (isset($this->dateArray)) {
            return;
        }

        $j = $this->julianDay;

        // normalize to 0-700 years (255675 days)
        $c = intdiv($j, 255675);
        $j -= $c * 255675;
        // additional step to avoid int overflow on PHP_INT_MIN
        if ($j < 0) {
            $j += 255675;
            $c -= 1;
        }

        // https://en.wikipedia.org/wiki/Julian_day#Julian_or_Gregorian_calendar_from_Julian_day_number
        $f = $j + 1401;
        $e = 4 * $f + 3;
        $g = intdiv($e % 1461, 4);
        $h = 5 * $g + 2;

        $d = intdiv($h % 153, 5) + 1;
        $m = (intdiv($h, 153) + 2) % 12 + 1;
        $y = intdiv($e, 1461) - 4716 + intdiv(12 + 2 - $m, 12);

        $this->dateArray = [$y + $c * 700, $m, $d];
    }

    public function __debugInfo(): array
    {
        return [
            'julian' => $this->toString(),
            ...$this->toGregorian()->__debugInfo(),
        ];
    }
}

// phpcs:disable PSR1.Files.SideEffects.FoundWithSymbols
// load alias
class_exists(Calendars\JulianCalendarDate::class);
