<?php

declare(strict_types=1);

namespace Arokettu\Date;

use DomainException;
use LogicException;
use RangeException;
use UnexpectedValueException;

final readonly class CivilCalendar
{
    public const MIN        = 1794168; // 200-03-01

    public const ITALY      = 2299161; // 1582-10-15
    public const HUNGARY    = 2301004; // 1587-11-01
    public const DENMARK    = 2342032; // 1700-03-01
    public const BRITAIN    = 2361222; // 1752-09-14
    public const SWEDEN     = 2361390; // 1753-03-01 // disregarding the madness of 1700-1712
    public const ALBANIA    = 2419735; // 1912-11-28
    public const BULGARIA   = 2420968; // 1916-04-14
    public const RUSSIA     = 2421639; // 1918-02-14
    public const ESTONIA    = 2421654; // 1918-03-01
    public const YUGOSLAVIA = 2421987; // 1919-01-28
    public const GREECE     = 2423480; // 1923-03-01

    public function __construct(
        public Date $switchDay
    ) {
        if ($switchDay->julianDay < self::MIN) {
            throw new DomainException(\sprintf(
                'Switch day cannot be earlier than "200-03-01", "%s" (Julian day %d) given',
                $switchDay,
                $switchDay->julianDay,
            ));
        }
    }

    public static function for(Date|int $switchDay): self
    {
        return new self($switchDay instanceof Date ? $switchDay : new Date($switchDay));
    }

    public function create(int $y, Month|int $m, int $d): Date
    {
        // try gregorian
        try {
            $gregDate = Calendar::create($y, $m, $d);
        } catch (RangeException|DomainException $ge) {
            $gregDate = false;
        }

        if ($gregDate && $gregDate->julianDay >= $this->switchDay->julianDay) {
            return $gregDate;
        }

        try {
            $julDate = JulianCalendar::create($y, $m, $d);
        } catch (RangeException|DomainException $je) {
            $julDate = false;
        }

        if ($julDate && $julDate->julianDay < $this->switchDay->julianDay) {
            return $julDate;
        }

        // try to give user an accurate error description

        // if both dates are set, it must be the gap date
        if ($gregDate && $julDate) {
            throw new DomainException(\sprintf(
                '"%s" likely belongs to the switch gap. Dates between "%s" and "%s" are invalid',
                $gregDate,
                $this->switchDay->subDays(1)->julian(),
                $this->switchDay,
            ));
        }

        $ge ??= null;
        $je ??= null;

        if ($ge instanceof RangeException || $je instanceof RangeException) {
            throw new RangeException('Date value overflow', previous: $ge ?? $je);
        }

        $gregorianMessage = $gregDate ?
            // Likely impossible
            'Gregorian is not applicable because the date is before the switch date' : // @codeCoverageIgnore
            $ge?->getMessage() ?? throw new LogicException('Invalid Gregorian state: not valid and no exception');

        $julianMessage = $julDate ?
            'Julian is not applicable because the date is on or after the switch date' :
            $je?->getMessage() ?? throw new LogicException('Invalid Julian state: not valid and no exception');

        throw new DomainException(\sprintf(
            'Unable to parse the due to errors: Gregorian: "%s", Julian: "%s"',
            $gregorianMessage,
            $julianMessage,
        ), previous: $ge ?? $je);
    }

    public function parse(string $string): Date
    {
        return self::fromString($string);
    }

    public function fromString(string $string): Date
    {
        if (!preg_match('/^(-?\d+)-(\d+)-(\d+)$/', $string, $matches)) {
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

    public function dateToString(Date $date): string
    {
        return $date->civil($this->switchDay->julianDay)->toString();
    }

    public function civilDate(Date $date): Calendars\CivilDate
    {
        return $date->civil($this->switchDay->julianDay);
    }
}
