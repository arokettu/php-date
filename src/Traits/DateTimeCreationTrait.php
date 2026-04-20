<?php

/**
 * @copyright 2024 Anton Smirnov
 * @license MIT https://spdx.org/licenses/MIT.html
 */

declare(strict_types=1);

namespace Arokettu\Date\Traits;

use Arokettu\Date\Date;
use Arokettu\Date\DateInterface;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;

/**
 * @internal
 */
trait DateTimeCreationTrait
{
    abstract public static function fromDateInterface(DateInterface $date): self;

    public static function fromDateTime(DateTimeInterface $dateTime): self
    {
        $y = \intval($dateTime->format('Y'));
        $m = \intval($dateTime->format('m'));
        $d = \intval($dateTime->format('d'));

        return self::fromDateInterface(Date::create($y, $m, $d));
    }

    public static function today(DateTimeZone|null $timeZone = null): self
    {
        return self::fromDateTime(new DateTimeImmutable('today', $timeZone));
    }

    public static function parseDateTimeString(string $string, DateTimeZone|null $timeZone = null): self
    {
        return self::fromDateTime(new DateTimeImmutable($string, $timeZone));
    }

    public static function fromTimestamp(int|float $timestamp, DateTimeZone|null $timeZone = null): self
    {
        if (PHP_VERSION_ID >= 80400) {
            // @codeCoverageIgnoreStart
            $dt = DateTimeImmutable::createFromTimestamp($timestamp);
            // @codeCoverageIgnoreEnd
        } elseif (\is_int($timestamp)) {
            $dt = DateTimeImmutable::createFromFormat('U', (string)$timestamp);
        } else {
            $dt = DateTimeImmutable::createFromFormat('U', \sprintf('%.0F', $timestamp));
        }

        if ($timeZone) {
            $dt = $dt->setTimezone($timeZone);
        }

        return self::fromDateTime($dt);
    }
}
