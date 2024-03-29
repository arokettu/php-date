<?php

declare(strict_types=1);

namespace Arokettu\Date\Tests;

use Arokettu\Date\Date;
use Arokettu\Date\MilankovicCalendar;
use Arokettu\Date\WeekDay;
use PHPUnit\Framework\TestCase;

class MilankovicRangeEdgeCasesTest extends TestCase
{
    public function testZero(): void
    {
        $date1 = Date::createFromJulianDay(0);
        self::assertEquals('-4713-11-22', (string)$date1->milankovic()); // Jan 1, 4713 BCE (-4712) Julian
        self::assertEquals(WeekDay::Monday, $date1->getWeekDay());

        $date2 = MilankovicCalendar::create(-4713, 11, 22);
        self::assertEquals($date1, $date2);
    }

    public function testPhpIntMax64(): void
    {
        if (PHP_INT_SIZE < 8) {
            $this->markTestSkipped();
        }

        $date1 = Date::createFromJulianDay(9223372036854775807);
        self::assertEquals('25252754133231977-10-12', (string)$date1->milankovic());
        self::assertEquals(WeekDay::Monday, $date1->getWeekDay());

        $date2 = MilankovicCalendar::create(25252754133231977, 10, 12);
        self::assertEquals($date1, $date2);
    }

    public function testPhpIntMin64(): void
    {
        if (PHP_INT_SIZE < 8) {
            $this->markTestSkipped();
        }

        $date1 = Date::createFromJulianDay(-9223372036854775807 - 1);
        self::assertEquals('-25252754133241402-01-01', (string)$date1->milankovic());
        self::assertEquals(WeekDay::Sunday, $date1->getWeekDay());

        $date2 = MilankovicCalendar::create(-25252754133241402, 1, 1);
        self::assertEquals($date1, $date2);
    }

    public function testPhpIntMax32(): void
    {
        $date1 = Date::createFromJulianDay(2147483647);
        self::assertEquals('5874902-11-21', (string)$date1->milankovic());
        self::assertEquals(WeekDay::Tuesday, $date1->getWeekDay());

        $date2 = MilankovicCalendar::create(5874902, 11, 21);
        self::assertEquals($date1, $date2);
    }

    public function testPhpIntMin32(): void
    {
        $date1 = Date::createFromJulianDay(-2147483647 - 1);
        self::assertEquals('-5884202-03-16', (string)$date1->milankovic());
        self::assertEquals(WeekDay::Saturday, $date1->getWeekDay());

        $date2 = MilankovicCalendar::create(-5884202, 3, 16);
        self::assertEquals($date1, $date2);
    }

    public function testMaxOverflow(): void
    {
        if (PHP_INT_SIZE < 8) {
            $this->markTestSkipped();
        }

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Date value overflow');

        MilankovicCalendar::create(25252754133231977, 10, 13);
    }

    public function testMinOverflow(): void
    {
        if (PHP_INT_SIZE < 8) {
            $this->markTestSkipped();
        }

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Date value overflow');

        MilankovicCalendar::create(-25252754133241403, 12, 31);
    }
}
