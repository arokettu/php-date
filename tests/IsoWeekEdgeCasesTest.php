<?php

declare(strict_types=1);

namespace Arokettu\Date\Tests;

use Arokettu\Date\Date;
use Arokettu\Date\IsoWeekCalendar;
use Arokettu\Date\WeekDay;
use PHPUnit\Framework\TestCase;

class IsoWeekEdgeCasesTest extends TestCase
{
    public function testZero(): void
    {
        $date1 = Date::createFromJulianDay(0);
        self::assertEquals('-4713-W48-1', (string)$date1->isoWeek()); // Jan 1, 4713 BCE (-4712) Julian
        self::assertEquals(WeekDay::Monday, $date1->isoWeek()->getWeekDay());

        $date2 = IsoWeekCalendar::create(-4713, 48, 1);
        self::assertEquals($date1, $date2);
    }

    public function testPhpIntMax64(): void
    {
        if (PHP_INT_SIZE < 8) {
            $this->markTestSkipped();
        }

        $date1 = Date::createFromJulianDay(9223372036854775807);
        self::assertEquals('25252734927761842-W25-1', (string)$date1->isoWeek());
        self::assertEquals(WeekDay::Monday, $date1->isoWeek()->getWeekDay());

        $date2 = IsoWeekCalendar::create(25252734927761842, 25, 1);
        self::assertEquals($date1, $date2);
    }

    public function testPhpIntMin64(): void
    {
        if (PHP_INT_SIZE < 8) {
            $this->markTestSkipped();
        }

        $date1 = Date::createFromJulianDay(-9223372036854775807 - 1);
        self::assertEquals('-25252734927771267-W17-7', (string)$date1->isoWeek());
        self::assertEquals(WeekDay::Sunday, $date1->isoWeek()->getWeekDay());

        $date2 = IsoWeekCalendar::create(-25252734927771267, 17, 7);
        self::assertEquals($date1, $date2);
    }

    public function testPhpIntMax32(): void
    {
        $date1 = Date::createFromJulianDay(2147483647);
        self::assertEquals('5874898-W23-2', (string)$date1->isoWeek());
        self::assertEquals(WeekDay::Tuesday, $date1->isoWeek()->getWeekDay());

        $date2 = IsoWeekCalendar::create(5874898, 23, 2);
        self::assertEquals($date1, $date2);
    }

    public function testPhpIntMin32(): void
    {
        $date1 = Date::createFromJulianDay(-2147483647 - 1);
        self::assertEquals('-5884323-W19-6', (string)$date1->isoWeek());
        self::assertEquals(WeekDay::Saturday, $date1->isoWeek()->getWeekDay());

        $date2 = IsoWeekCalendar::create(-5884323, 19, 6);
        self::assertEquals($date1, $date2);
    }

    public function testMaxOverflow(): void
    {
        if (PHP_INT_SIZE < 8) {
            $this->markTestSkipped();
        }

        $this->expectException(\RangeException::class);
        $this->expectExceptionMessage('Date value overflow');

        IsoWeekCalendar::create(25252734927761842, 25, 2);
    }

    public function testMinOverflow(): void
    {
        if (PHP_INT_SIZE < 8) {
            $this->markTestSkipped();
        }

        $this->expectException(\RangeException::class);
        $this->expectExceptionMessage('Date value overflow');

        IsoWeekCalendar::create(-25252734927771267, 17, 6);
    }
}
