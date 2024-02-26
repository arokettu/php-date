<?php

declare(strict_types=1);

namespace Arokettu\Date;

use Arokettu\Date\Helpers\CacheHelper;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use DomainException;
use Stringable;
use WeakMap;

final readonly class Date implements Stringable
{
    public function __construct(
        public int $julianDay,
    ) {
    }

    // various getters

    public function getJulianDay(): int
    {
        return $this->julianDay;
    }

    public function getWeekDay(): WeekDay
    {
        $wd = $this->julianDay % 7 + 1;
        return WeekDay::from($wd > 0 ? $wd : $wd + 7);
    }

    public function getDateArray(): array
    {
        CacheHelper::$dateArray ??= new WeakMap();
        if (isset(CacheHelper::$dateArray[$this])) {
            return CacheHelper::$dateArray[$this];
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

        return CacheHelper::$dateArray[$this] = [$y + $c * 400, $m, $d];
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

    // ymd factory

    private static function fromGregorianRaw(int $y, int $m, int $d): self
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

        $monthCorrection = intdiv($m - 14, 12);
        $julianDay =
            intdiv(1461 * ($y + 4800 + $monthCorrection), 4) +
            intdiv(367 * ($m - 2 - 12 * $monthCorrection), 12) -
            intdiv(3 * (intdiv($y + 4900 + $monthCorrection, 100)), 4) +
            $d - 32075;
        $julianDay += $c1 * 146097;
        $julianDay += $c2 * 146097;

        return new self($julianDay);
    }

    public static function today(?DateTimeZone $timeZone = null): self
    {
        return self::fromDateTime(new DateTimeImmutable('now', $timeZone));
    }

    public static function create(int $y, Month|int $m, int $d): self
    {
        if ($m instanceof Month) {
            $mo = $m;
            $mi = $m->value;
        } else {
            $mo = Month::tryFrom($m) ??
                throw new DomainException('Month must be an instance of Month or an integer 1-12');
            $mi = $m;
        }

        $days = $mo->days($y);

        if ($d < 1 || $d > $days) {
            throw new DomainException("For year $y month $mi, day must be in range 1-$days");
        }

        return self::fromGregorianRaw($y, $mi, $d);
    }

    public static function parse(string $string): self
    {
        if (!preg_match('/(-?\d+)-(\d+)-(\d+)/', $string, $matches)) {
            throw new DomainException('Unable to parse the date string: ' . $string);
        }

        [/* $_ */, $y, $m, $d] = $matches;

        return self::create(\intval($y), \intval($m), \intval($d));
    }

    // DateTime conversion

    public static function fromDateTime(DateTimeInterface $dateTime): self
    {
        // https://en.wikipedia.org/wiki/Julian_day#Converting_Gregorian_calendar_date_to_Julian_Day_Number
        $y = \intval($dateTime->format('Y'));
        $m = \intval($dateTime->format('m'));
        $d = \intval($dateTime->format('d'));

        return self::fromGregorianRaw($y, $m, $d);
    }

    public function toDateTime(?DateTimeZone $timeZone = null): DateTimeImmutable
    {
        $ymd = $this->getDateArray();
        return (new DateTimeImmutable('today', $timeZone))->setDate($ymd[0], $ymd[1], $ymd[2]);
    }

    public static function parseDateTimeString(string $string, ?DateTimeZone $timeZone = null): self
    {
        return self::fromDateTime(new DateTimeImmutable($string, $timeZone));
    }

    public function formatDateTime(string $format): string
    {
        return $this->toDateTime()->format($format);
    }

    // arithmetic

    public function add(int $days): self
    {
        return new self($this->julianDay + $days);
    }

    public function subDays(int $days): self
    {
        return new self($this->julianDay - $days);
    }

    public function sub(Date $date): int
    {
        return $this->julianDay - $date->julianDay;
    }

    // magic

    public function __serialize(): array
    {
        return [$this->julianDay];
    }

    public function __unserialize(array $data): void
    {
        [$this->julianDay] = $data;
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public function __debugInfo(): array
    {
        return [
            'date' => $this->toString(),
            'julianDay' => $this->julianDay,
        ];
    }
}
