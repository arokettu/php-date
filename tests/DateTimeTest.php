<?php

declare(strict_types=1);

namespace Arokettu\Date\Tests;

use Arokettu\Date\Calendar;
use Arokettu\Date\Date;
use PHPUnit\Framework\TestCase;

class DateTimeTest extends TestCase
{
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
}
