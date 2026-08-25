<?php

/**
 * @copyright 2024 Anton Smirnov
 * @license MIT https://spdx.org/licenses/MIT.html
 */

declare(strict_types=1);

namespace Arokettu\Date;

use Arokettu\Date\Helpers\YearHelper;
use DateTimeImmutable;
use DateTimeZone;
use DomainException;
use RangeException;
use UnexpectedValueException;

final readonly class IsoWeekDate implements DateInterface
{
    use Traits\BaseTrait;
    use Traits\ConversionTrait;
    use Traits\WeekTrait;
    use Traits\DateTimeCreationTrait;

    private const Y400_DAYS = 146097;
    private const Y400_YEARS = 400;
    private const Y4_DAYS = 1461;
    private const Y4_YEARS = 4;
    private const BASE_DAY = 1721060; // 0-1-1 Gregorian

    private array $dateArray;

    public function __construct(
        public int $julianDay,
    ) {
        $this->init();
    }

    private function copyWith(int $julianDay): self
    {
        return new self($julianDay);
    }

    public function toIsoWeek(): self
    {
        return $this; // optimize
    }

    public static function create(int $y, int $w, WeekDay|int $d): self
    {
        if ($d instanceof WeekDay) {
            $di = $d->value;
        } else {
            WeekDay::tryFrom($d) ??
                throw new DomainException('Day must be an instance of WeekDay or an integer 1-7');
            $di = $d;
        }

        $weeks = YearHelper::weeksInIsoYear($y);

        if ($w < 1 || $w > $weeks) {
            throw new DomainException("For year $y, week must be in range 1-$weeks");
        }

        return self::fromRaw($y, $w, $di);
    }

    public static function parse(string $string): self
    {
        return self::fromString($string);
    }

    public static function fromString(string $string): self
    {
        if (
            !preg_match('/^(-?\d+)-W?(\d+)-(\d+)$/i', $string, $matches) &&
            !preg_match('/^(-?\d+)W(\d{2})(\d)$/i', $string, $matches)
        ) {
            throw new UnexpectedValueException(\sprintf('Unable to parse the date string: "%s"', $string));
        }

        [/* $_ */, $y, $m, $d] = $matches;

        try {
            return self::create(\intval($y), \intval($m), \intval($d));
        } catch (DomainException $e) {
            throw new UnexpectedValueException(
                \sprintf('Unable to parse the date string: "%s". %s', $string, $e->getMessage()),
                previous: $e,
            );
        }
    }

    private static function fromRaw(int $y, int $w, int $d): self
    {
        // normalize to 1..400 years (146097 days)
        if ($y >= 0) {
            $c1 = intdiv($y, self::Y400_YEARS);
            $c2 = 0;
        } else {
            // this insane code here is to avoid int overflow on PHP_INT_MIN
            // because simple logic with $c1 * 328718 may overflow, so we split one correction with two
            // that's guaranteed to be in range as long as the final result is in range
            $c1 = intdiv($y, self::Y400_YEARS) - 1;
            $c2 = intdiv($c1, 2);
            $c1 -= $c2;
        }
        $y -= ($c1 + $c2) * self::Y400_YEARS;

        $jan4WeekDay = YearHelper::wdIsoYearJan4($y);
        $dayOfYear = $w * 7 + $d - $jan4WeekDay - 4;

        $yearDays = YearHelper::isGregorianLeap($y) ? 366 : 365;

        if ($dayOfYear < 1) {
            $y -= 1;
            $yearDays = YearHelper::isGregorianLeap($y) ? 366 : 365;
            $dayOfYear += $yearDays;
        } elseif ($dayOfYear > $yearDays) {
            $y += 1;
            $dayOfYear -= $yearDays;
        }

        $julianDay = 365 * $y
            + intdiv($y + 3, 4)
            - intdiv($y + 99, 100)
            + intdiv($y + 399, 400)
            + $dayOfYear + self::BASE_DAY;

        // apply back correction
        $julianDay += $c1 * self::Y400_DAYS;
        $julianDay += $c2 * self::Y400_DAYS;

        if (\is_integer($julianDay) === false) {
            throw new RangeException('Date value overflow');
        }

        return new self($julianDay);
    }

    public function init(): void
    {
        if (isset($this->dateArray)) {
            return;
        }

        $j = $this->julianDay;

        // normalize to 0-400 years (146097 days)
        $cycle = intdiv($j, self::Y400_DAYS) - 13;
        if ($cycle >= 0) {
            $j -= $cycle * self::Y400_DAYS;
        } else {
            // prevent int_min overflow
            $c1 = intdiv($cycle, 2);
            $c2 = $cycle - $c1;
            $j -= $c1 * self::Y400_DAYS;
            $j -= $c2 * self::Y400_DAYS;
        }

        // use a slightly different Gregorian algorithm that gives us day of the year as an intermediate step
        $dayFromBase = $j - self::BASE_DAY;
        $century = intdiv(self::Y4_YEARS * $dayFromBase - 1, self::Y400_DAYS);
        $dayOfCentury = $dayFromBase - intdiv(self::Y400_DAYS * $century, 4);

        $yearOfCentury = intdiv(self::Y4_YEARS * $dayOfCentury, self::Y4_DAYS);
        $dayOfYear = $dayOfCentury - intdiv(self::Y4_DAYS * $yearOfCentury - 1, self::Y4_YEARS);

        $year = 100 * $century + $yearOfCentury;

        $weekDay = $this->getWeekDayNumber();
        $week = intdiv($dayOfYear - $weekDay + 10, 7);
        $weeks = YearHelper::weeksInIsoYear($year);
        if ($week < 1) {
            $year -= 1;
            $week = YearHelper::weeksInIsoYear($year);
        } elseif ($week > $weeks) {
            $year += 1;
            $week = 1;
        }

        $this->dateArray = [$year + self::Y400_YEARS * $cycle, $week, $weekDay];
    }

    public function getYear(): int
    {
        return $this->dateArray[0];
    }

    public function getWeek(): int
    {
        return $this->dateArray[1];
    }

    public function getDateArray(): array
    {
        return $this->dateArray;
    }

    public function toDateTime(DateTimeZone|null $timeZone = null): DateTimeImmutable
    {
        return (new DateTimeImmutable('today', $timeZone))
            ->setISODate($this->dateArray[0], $this->dateArray[1], $this->dateArray[2]);
    }

    public function formatDateTime(string $format, DateTimeZone|null $timeZone = null): string
    {
        return $this->toDateTime($timeZone)->format($format);
    }

    public function toString(): string
    {
        return \sprintf('%d-W%02d-%d', $this->dateArray[0], $this->dateArray[1], $this->dateArray[2]);
    }

    public function __debugInfo(): array
    {
        return [
            'isoWeek' => $this->toString(),
            ...$this->toGregorian()->__debugInfo(),
        ];
    }
}

// phpcs:disable PSR1.Files.SideEffects.FoundWithSymbols
// load alias
class_exists(Calendars\IsoWeekDate::class);
