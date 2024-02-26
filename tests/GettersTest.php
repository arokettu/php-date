<?php

declare(strict_types=1);

namespace Arokettu\Date\Tests;

use Arokettu\Date\Date;
use Arokettu\Date\Month;
use Arokettu\Date\WeekDay;
use PHPUnit\Framework\TestCase;

class GettersTest extends TestCase
{
    public function testWeekDay(): void
    {
        $date = new Date(2460366);
        self::assertEquals(WeekDay::Sunday, $date->getWeekDay());

        $date = new Date(-2460366);
        self::assertEquals(WeekDay::Tuesday, $date->getWeekDay());
    }

    public function testJulianDay(): void
    {
        $date = new Date(2460366);
        self::assertEquals(2460366, $date->getJulianDay());

        $date = new Date(-2460366);
        self::assertEquals(-2460366, $date->getJulianDay());
    }

    public function testGregorianDate(): void
    {
        $date = new Date(2460366);

        self::assertEquals('2024-02-25', $date->toString());
        self::assertEquals(2024, $date->getYear());
        self::assertEquals(Month::February, $date->getMonth());
        self::assertEquals(2, $date->getMonthNumber());
        self::assertEquals(25, $date->getDay());
    }

    public function testGregorianDateNegJD(): void
    {
        $date = new Date(-2460366);

        self::assertEquals('-11449-08-24', $date->toString());
        self::assertEquals(-11449, $date->getYear());
        self::assertEquals(Month::August, $date->getMonth());
        self::assertEquals(8, $date->getMonthNumber());
        self::assertEquals(24, $date->getDay());
    }
}
