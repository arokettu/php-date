<?php

/**
 * @copyright 2024 Anton Smirnov
 * @license MIT https://spdx.org/licenses/MIT.html
 */

declare(strict_types=1);

namespace Arokettu\Date;

use Arokettu\Date\Helpers\CacheHelper;
use RangeException;
use WeakMap;

final readonly class Date implements DateInterface
{
    use Traits\BaseTrait;
    use Traits\DeprecatedTrait;
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

    public function toGregorian(): Date
    {
        return $this; // optimize
    }

    // date to julian

    private static function fromRaw(int $y, int $m, int $d): self
    {
        // normalize to 0..400 years (146097 days)
        if ($y >= 0) {
            $c1 = intdiv($y, 400);
            $c2 = 0;
        } else {
            // this insane code here is to avoid int overflow on PHP_INT_MIN
            // because simple logic with $c1 * 146097 may overflow, so we split one correction with two
            // that's guaranteed to be in range as long as the final result is in range
            $c1 = intdiv($y, 400) - 1;
            $c2 = intdiv($c1, 2);
            $c1 -= $c2;
        }
        $y -= ($c1 + $c2) * 400;

        // https://en.wikipedia.org/wiki/Julian_day#Converting_Gregorian_calendar_date_to_Julian_Day_Number
        $monthCorrection = intdiv($m - 14, 12);
        $julianDay =
            intdiv(1461 * ($y + 4800 + $monthCorrection), 4) +
            intdiv(367 * ($m - 2 - 12 * $monthCorrection), 12) -
            intdiv(3 * (intdiv($y + 4900 + $monthCorrection, 100)), 4) +
            $d - 32075;
        $julianDay += $c1 * 146097;
        $julianDay += $c2 * 146097;

        if (\is_integer($julianDay) === false) {
            throw new RangeException('Date value overflow');
        }

        return new Date($julianDay);
    }

    private static function getMonthDays(int $year, Month $month): int
    {
        return $month->gregorianDays($year);
    }

    // julian to date

    public function getDateArray(): array
    {
        return $this->dateArray;
    }

    public function init(): void
    {
        $j = $this->julianDay;

        // normalize to 0-400 years (146097 days)
        $c = intdiv($j, 146097);
        $j -= $c * 146097;
        // additional step to avoid int overflow on PHP_INT_MIN
        if ($j < 0) {
            $j += 146097;
            $c -= 1;
        }

        // https://en.wikipedia.org/wiki/Julian_day#Julian_or_Gregorian_calendar_from_Julian_day_number
        $f = $j + 1401 + intdiv(intdiv(4 * $j + 274277, 146097) * 3, 4) - 38;
        $e = 4 * $f + 3;
        $g = intdiv($e % 1461, 4);
        $h = 5 * $g + 2;

        $d = intdiv($h % 153, 5) + 1;
        $m = (intdiv($h, 153) + 2) % 12 + 1;
        $y = intdiv($e, 1461) - 4716 + intdiv(12 + 2 - $m, 12);

        $this->dateArray = [$y + $c * 400, $m, $d];
    }

    // alternative calendars

    public function isoWeek(): Calendars\IsoWeekDate
    {
        CacheHelper::$isoWeekDateObject ??= new WeakMap();
        return CacheHelper::$isoWeekDateObject[$this] ??= new Calendars\IsoWeekDate($this->julianDay);
    }

    public function julian(): Calendars\JulianCalendarDate
    {
        CacheHelper::$julianDateObject ??= new WeakMap();
        return CacheHelper::$julianDateObject[$this] ??= new Calendars\JulianCalendarDate($this->julianDay);
    }

    public function milankovic(): Calendars\MilankovicDate
    {
        CacheHelper::$milankovicDateObject ??= new WeakMap();
        return CacheHelper::$milankovicDateObject[$this] ??= new Calendars\MilankovicDate($this->julianDay);
    }

    public function civil(Date|int $switchDay): Calendars\CivilDate
    {
        if ($switchDay instanceof Date) {
            $switchDay = $switchDay->julianDay;
        }

        CacheHelper::$civilDateObject ??= new WeakMap();
        CacheHelper::$civilDateObject[$this] ??= [];
        return CacheHelper::$civilDateObject[$this][$switchDay] ??=
            new Calendars\CivilDate($this->julianDay, $switchDay);
    }

    // magic

    public function __debugInfo(): array
    {
        return [
            'julianDay'     => $this->julianDay,
            'weekDay'       => $this->getWeekDay()->name,
            'gregorian'     => $this->toString(),
        ];
    }
}

// phpcs:disable PSR1.Files.SideEffects.FoundWithSymbols
// load alias
class_exists(GregorianDate::class);
