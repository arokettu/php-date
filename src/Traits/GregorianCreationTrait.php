<?php

/**
 * @copyright 2024 Anton Smirnov
 * @license MIT https://spdx.org/licenses/MIT.html
 */

declare(strict_types=1);

namespace Arokettu\Date\Traits;

use Arokettu\Date\Date;
use Arokettu\Date\Month;
use DomainException;
use UnexpectedValueException;

trait GregorianCreationTrait
{
    // creation

    abstract private static function fromRaw(int $y, int $m, int $d): self;
    abstract private static function getMonthDays(int $year, Month $month): int;

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

        $days = self::getMonthDays($y, $mo);

        if ($d < 1 || $d > $days) {
            throw new DomainException("For year $y month $mi, day must be in range 1-$days");
        }

        return self::fromRaw($y, $mi, $d);
    }

    public static function parse(string $string): self
    {
        return self::fromString($string);
    }

    public static function fromString(string $string): self
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
}
