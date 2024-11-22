<?php

declare(strict_types=1);

namespace Arokettu\Date;

use DomainException;
use LogicException;
use RangeException;
use UnexpectedValueException;

final readonly class CivilCalendar
{
    public const ITALY = 2299161; // 1582-10-15
    public const BRITAIN = 2361222; // 1752-09-14
    public const RUSSIA = 2421639; // 1918-02-14
    public const ESTONIA = 2421654; // 1918-03-01
    public const YUGOSLAVIA = 2421987; // 1919-01-28
    public const SWEDEN = 2361390; // 1753-03-01 // disregarding the madness of 1700-1712
    public const ALBANIA = 2419735; // 1912-11-28
    public const BULGARIA = 2420968; // 1916-04-14
    public const DENMARK = 2342032; // 1700-03-01
    public const GREECE = 2423480; // 1923-03-01
    public const HUNGARY = 2301004; // 1587-11-01

    public static function create(int $switchDay, int $y, Month|int $m, int $d): Date
    {
        // try gregorian
        try {
            $gregDate = Calendar::create($y, $m, $d);
        } catch (RangeException|DomainException $ge) {
            $gregDate = false;
        }

        if ($gregDate && $gregDate->julianDay >= $switchDay) {
            return $gregDate;
        }

        try {
            $julDate = JulianCalendar::create($y, $m, $d);
        } catch (RangeException|DomainException $je) {
            $julDate = false;
        }

        if ($julDate && $julDate->julianDay < $switchDay) {
            return $julDate;
        }

        // try to give user an accurate error description

        throw new LogicException(
            'CivilCalendar entered an invalid state while trying to parse the date. ' .
            'Please report this as a bug'
        );
    }

    public static function parse(int $switchDay, string $string): Date
    {
        return self::fromString($switchDay, $string);
    }

    public static function fromString(int $switchDay, string $string): Date
    {
        if (!preg_match('/^(-?\d+)-(\d+)-(\d+)$/', $string, $matches)) {
            throw new UnexpectedValueException(sprintf('Unable to parse the date string: "%s"', $string));
        }

        [/* $_ */, $y, $m, $d] = $matches;

        try {
            return self::create($switchDay, \intval($y), \intval($m), \intval($d));
        } catch (DomainException $e) {
            throw new UnexpectedValueException(
                sprintf('Unable to parse the date string: "%s". %s', $string, $e->getMessage()),
                previous: $e,
            );
        }
    }
}
