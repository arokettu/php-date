<?php

declare(strict_types=1);

namespace Arokettu\Date\Tests;

use Arokettu\Date\Date;
use Arokettu\Date\JulianCalendar;
use Arokettu\Date\WeekDay;
use PHPUnit\Framework\TestCase;

class JulianRangeEdgeCasesTest extends TestCase
{
    public function testZero(): void
    {
        $date1 = Date::createFromJulianDay(0);
        self::assertEquals('-4712-01-01', (string)$date1->julian()); // Jan 1, 4713 BCE (-4712) Julian
        self::assertEquals(WeekDay::Monday, $date1->getWeekDay());

        $date2 = JulianCalendar::create(-4712, 1, 1);
        self::assertEquals($date1, $date2);
    }

    public function testPhpIntMax64(): void
    {
        if (PHP_INT_SIZE < 8) {
            $this->markTestSkipped();
        }

        $date1 = Date::createFromJulianDay(9223372036854775807);
        self::assertEquals('25252216391110348-05-22', (string)$date1->julian());
        self::assertEquals(WeekDay::Monday, $date1->getWeekDay());

        $date2 = JulianCalendar::create(25252216391110348, 5, 22);
        self::assertEquals($date1, $date2);
    }

    public function testPhpIntMin64(): void
    {
        if (PHP_INT_SIZE < 8) {
            $this->markTestSkipped();
        }

        $date1 = Date::createFromJulianDay(-9223372036854775807 - 1);
        self::assertEquals('-25252216391119773-08-11', (string)$date1->julian());
        self::assertEquals(WeekDay::Sunday, $date1->getWeekDay());

        $date2 = JulianCalendar::create(-25252216391119773, 8, 11);
        self::assertEquals($date1, $date2);
    }

    public function testPhpIntMax32(): void
    {
        $date1 = Date::createFromJulianDay(2147483647);
        self::assertEquals('5874777-10-17', (string)$date1->julian());
        self::assertEquals(WeekDay::Tuesday, $date1->getWeekDay());

        $date2 = JulianCalendar::create(5874777, 10, 17);
        self::assertEquals($date1, $date2);
    }

    public function testPhpIntMin32(): void
    {
        $date1 = Date::createFromJulianDay(-2147483647 - 1);
        self::assertEquals('-5884202-03-16', (string)$date1->julian());
        self::assertEquals(WeekDay::Saturday, $date1->getWeekDay());

        $date2 = JulianCalendar::create(-5884202, 3, 16);
        self::assertEquals($date1, $date2);
    }

    public function testMaxOverflow(): void
    {
        if (PHP_INT_SIZE < 8) {
            $this->markTestSkipped();
        }

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Date value overflow');

        JulianCalendar::create(25252216391110348, 5, 23);
    }

    public function testMinOverflow(): void
    {
        if (PHP_INT_SIZE < 8) {
            $this->markTestSkipped();
        }

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Date value overflow');

        JulianCalendar::create(-25252216391119773, 8, 10);
    }
}
