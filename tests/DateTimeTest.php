<?php

/**
 * @copyright 2024 Anton Smirnov
 * @license MIT https://spdx.org/licenses/MIT.html
 */

declare(strict_types=1);

namespace Arokettu\Date\Tests;

use Arokettu\Date\Calendar;
use Arokettu\Date\Date;
use DateTimeZone;
use PHPUnit\Framework\TestCase;

final class DateTimeTest extends TestCase
{
    private string $tz;

    protected function setUp(): void
    {
        $this->tz = date_default_timezone_get();
        date_default_timezone_set('UTC');
    }

    protected function tearDown(): void
    {
        date_default_timezone_set($this->tz);
    }

    public function testFromDateTime(): void
    {
        $dt = new \DateTime('2024-02-25');
        $date = Calendar::fromDateTime($dt);

        self::assertEquals(2460366, $date->getJulianDay());
    }

    public function testToDateTime(): void
    {
        $dt = new \DateTime('2024-02-25');
        $date = new Date(2460366);

        self::assertEquals($dt, $date->toDateTime());
    }

    public function testParse(): void
    {
        $date = Calendar::parseDateTimeString('17 April 1996');

        self::assertEquals('1996-04-17', (string)$date);
    }

    public function testFormat(): void
    {
        $date = Calendar::create(2050, 9, 1);

        self::assertEquals('09/01/50', $date->formatDateTime('m/d/y'));
    }

    public function testTimestamp(): void
    {
        $ts = 1768133126;
        $tsms = 1768133126.249846;

        // UTC
        self::assertEquals('2026-01-11', (string)Calendar::fromTimestamp($ts));
        self::assertEquals('2026-01-11', (string)Calendar::fromTimestamp($tsms));

        // Different date because of TZ
        self::assertEquals('2026-01-12', (string)Calendar::fromTimestamp($ts, new DateTimeZone('Pacific/Auckland')));
        self::assertEquals('2026-01-12', (string)Calendar::fromTimestamp($tsms, new DateTimeZone('Pacific/Auckland')));
    }
}
