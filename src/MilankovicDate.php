<?php

/**
 * @copyright 2024 Anton Smirnov
 * @license MIT https://spdx.org/licenses/MIT.html
 */

declare(strict_types=1);

namespace Arokettu\Date;

use RangeException;

final readonly class MilankovicDate implements DateInterface
{
    use Traits\BaseTrait;
    use Traits\ConversionTrait;
    use Traits\WeekTrait;
    use Traits\GregorianGettersTrait;
    use Traits\GregorianCreationTrait;
    use Traits\DateTimeGettersTrait;
    use Traits\DateTimeCreationTrait;

    private const Y900_DAYS = 328718;
    private const Y900_YEARS = 900;
    private const BASE_DAY = 1721119; // 0-2-28 Milankovic

    public function __construct(
        public int $julianDay,
    ) {
        $this->init();
    }

    private function copyWith(int $julianDay): self
    {
        return new self($julianDay);
    }

    public function toMilankovic(): self
    {
        return $this; // optimize
    }

    // date to milankovic

    private static function fromRaw(int $y, int $m, int $d): self
    {
        // normalize to 0..900 years (328718 days)
        if ($y >= 0) {
            $c1 = intdiv($y, self::Y900_YEARS);
            $c2 = 0;
        } else {
            // this insane code here is to avoid int overflow on PHP_INT_MIN
            // because simple logic with $c1 * 328718 may overflow, so we split one correction with two
            // that's guaranteed to be in range as long as the final result is in range
            $c1 = intdiv($y, self::Y900_YEARS) - 1;
            $c2 = intdiv($c1, 2);
            $c1 -= $c2;
        }
        $y -= ($c1 + $c2) * self::Y900_YEARS;

        $m -= 3; // 0 = March
        if ($m < 0) {
            $y -= 1;
            $m += 12;
        }
        if ($y < 0) {
            $c1 -= 1;
            $y += self::Y900_YEARS;
        }
        $c = intdiv($y, 100);
        $yc = $y % 100;
        $julianDay =
            intdiv(self::Y900_DAYS * $c + 6, 9) +
            intdiv(36525 * $yc, 100) +
            intdiv(153 * $m + 2, 5) +
            $d + self::BASE_DAY;

        // apply back correction
        $julianDay += $c1 * self::Y900_DAYS;
        $julianDay += $c2 * self::Y900_DAYS;

        if (\is_integer($julianDay) === false) {
            throw new RangeException('Date value overflow');
        }

        return new self($julianDay);
    }

    private static function getMonthDays(int $year, Month $month): int
    {
        return $month->milankovicDays($year);
    }

    // julian to date

    public function init(): void
    {
        if (isset($this->dateArray)) {
            return;
        }

        $j = $this->julianDay;

        // normalize to 0-900 years (328718 days)
        $c = intdiv($j, self::Y900_DAYS) - 7;
        if ($c >= 0) {
            $j -= $c * self::Y900_DAYS;
        } else {
            // prevent int_min overflow
            $c1 = intdiv($c, 2);
            $c2 = $c - $c1;
            $j -= $c1 * self::Y900_DAYS;
            $j -= $c2 * self::Y900_DAYS;
        }

        $d = 9 * ($j - self::BASE_DAY - 1) + 2;
        $e = intdiv($d, self::Y900_DAYS);
        $dd = 100 * intdiv($d % self::Y900_DAYS, 9) + 99;
        $yy = intdiv($dd, 36525);
        $yd = 5 * intdiv($dd % 36525, 100) + 2;
        $mm = intdiv($yd, 153);
        $mc = intdiv($mm + 2, 12);

        $y = 100 * $e + $yy + $mc;
        $m = $mm - 12 * $mc + 3;
        $d = intdiv($yd % 153, 5) + 1;

        $this->dateArray = [$y + $c * 900, $m, $d];
    }

    public function __debugInfo(): array
    {
        return [
            'milankovic' => $this->toString(),
            ...$this->toGregorian()->__debugInfo(),
        ];
    }
}

// phpcs:disable PSR1.Files.SideEffects.FoundWithSymbols
// load alias
class_exists(Calendars\MilankovicDate::class);
