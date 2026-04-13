<?php

/**
 * @copyright 2024 Anton Smirnov
 * @license MIT https://spdx.org/licenses/MIT.html
 */

declare(strict_types=1);

namespace Arokettu\Date;

use Arokettu\Date\Helpers\CacheHelper;
use DateTimeImmutable;
use DateTimeZone;
use WeakMap;

final readonly class Date implements DateInterface
{
    use Traits\BaseTrait;
    use Traits\DeprecatedTrait;
    use Traits\WeekTrait;

    public function __construct(
        public int $julianDay,
    ) {
    }

    private function copyWith(int $julianDay): self
    {
        return new self($julianDay);
    }

    public function toGregorian(): Date
    {
        return $this; // optimize
    }

    // various getters

    public function getDateArray(): array
    {
        $cache = CacheHelper::$dateArray ??= new WeakMap();
        if (isset($cache[$this])) {
            return $cache[$this];
        }

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

        return $cache[$this] = [$y + $c * 400, $m, $d];
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
        return \sprintf('%d-%02d-%02d', $ymd[0], $ymd[1], $ymd[2]);
    }

    public static function today(DateTimeZone|null $timeZone = null): self
    {
        return Calendar::fromDateTime(new DateTimeImmutable('today', $timeZone));
    }

    // DateTime conversion

    public function toDateTime(DateTimeZone|null $timeZone = null): DateTimeImmutable
    {
        $ymd = $this->getDateArray();
        return (new DateTimeImmutable('today', $timeZone))->setDate($ymd[0], $ymd[1], $ymd[2]);
    }

    public function formatDateTime(string $format, DateTimeZone|null $timeZone = null): string
    {
        return $this->toDateTime($timeZone)->format($format);
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
            'isoWeek'       => $this->isoWeek()->toString(),
            'julian'        => $this->julian()->toString(),
            'milankovic'    => $this->milankovic()->toString(),
        ];
    }
}
