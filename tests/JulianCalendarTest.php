<?php

declare(strict_types=1);

namespace Arokettu\Date\Tests;

use Arokettu\Date\Calendars\JulianCalendarDate;
use Arokettu\Date\Date;
use Arokettu\Date\Month;
use Arokettu\Date\WeekDay;
use PHPUnit\Framework\TestCase;

class JulianCalendarTest extends TestCase
{
    public function testGetters(): void
    {
        $date = JulianCalendarDate::parse('2024-03-16');

        self::assertEquals(Date::parse('2024-03-29'), $date->getDate());
        self::assertEquals(16, $date->getDay());
        self::assertEquals(Month::March, $date->getMonth());
        self::assertEquals(3, $date->getMonthNumber());
        self::assertEquals(2024, $date->getYear());
        self::assertEquals(WeekDay::Friday, $date->getWeekDay());
        self::assertEquals(5, $date->getWeekDayNumber());
        self::assertEquals(2460399, $date->getJulianDay());
    }

    public function testSameInstance(): void
    {
        $date = Date::today();

        self::assertTrue($date->julian() === $date->julian());
    }

    public function testSerialize(): void
    {
        $date = JulianCalendarDate::parse('2024-03-16');

        self::assertEquals($date, unserialize(serialize($date)));
    }

    public function testDebugInfo(): void
    {
        $date = JulianCalendarDate::parse('2024-03-16');

        self::assertEquals([
            'calendarDate' => '2024-03-16',
            'baseDate' => [
                'julianDay' => 2460399,
                'date' => '2024-03-29',
            ],
        ], $date->__debugInfo());
    }

    public function testArithmetic(): void
    {
        $date1 = JulianCalendarDate::parse('2100-01-01');
        $date2 = JulianCalendarDate::parse('2100-04-10');

        self::assertEquals($date2, $date1->add(100));
        self::assertEquals($date1, $date2->subDays(100));
        self::assertEquals(100, $date2->sub($date1));
        self::assertEquals(100, $date2->sub($date1->date)); // subtract base class
    }

    public function testChristmas(): void
    {
        // 21st century
        $christmas = JulianCalendarDate::parse('2023-12-25');
        self::assertEquals('2024-01-07', $christmas->date->toString());
        self::assertEquals('2023-12-25', $christmas->toString());

        // 18th century
        $christmas = JulianCalendarDate::parse('1823-12-25');
        self::assertEquals('1824-01-06', $christmas->date->toString()); // a day less difference
        self::assertEquals('1823-12-25', $christmas->toString());

        // 23rd century
        $christmas = JulianCalendarDate::parse('2223-12-25');
        self::assertEquals('2224-01-09', $christmas->date->toString()); // 2 days more difference
        self::assertEquals('2223-12-25', $christmas->toString());
    }

    public function testLeapYears(): void
    {
        // non leap
        $date = JulianCalendarDate::create(2014, 2, 28);
        self::assertEquals('2014-03-01', (string)$date->add(1));
        // negative non leap
        $date = JulianCalendarDate::create(-5014, 2, 28);
        self::assertEquals('-5014-03-01', (string)$date->add(1));

        // leap
        $date = JulianCalendarDate::create(2016, 2, 28);
        self::assertEquals('2016-02-29', (string)$date->add(1));
        // negative leap
        $date = JulianCalendarDate::create(-5016, 2, 28);
        self::assertEquals('-5016-02-29', (string)$date->add(1));

        // non leap in gregorian, leap in julian
        $date = JulianCalendarDate::create(1900, 2, 28);
        self::assertEquals('1900-02-29', (string)$date->add(1));
        // negative non leap in gregorian, leap in julian
        $date = JulianCalendarDate::create(-5000, 2, 28);
        self::assertEquals('-5000-02-29', (string)$date->add(1));

        // leap century
        $date = JulianCalendarDate::create(2400, 2, 28);
        self::assertEquals('2400-02-29', (string)$date->add(1));
        // negative leap century
        $date = JulianCalendarDate::create(-5200, 2, 28);
        self::assertEquals('-5200-02-29', (string)$date->add(1));
    }

    public function testCreateLeapYear(): void
    {
        // leap
        $date = JulianCalendarDate::create(2100, 2, 29);
        self::assertEquals('2100-02-29', (string)$date);
        // negative leap
        $date = JulianCalendarDate::create(-5100, 2, 29);
        self::assertEquals('-5100-02-29', (string)$date);

        // leap century
        $date = JulianCalendarDate::create(2100, 2, 29);
        self::assertEquals('2100-02-29', (string)$date);
        // negative leap century
        $date = JulianCalendarDate::create(-5100, 2, 29);
        self::assertEquals('-5100-02-29', (string)$date);
    }

    public function testCreateLeapYearNonLeap(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('For year 2014 month 2, day must be in range 1-28');

        JulianCalendarDate::create(2014, 2, 29);
    }

    public function testCreateLeapYearNonLeapNeg(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('For year -5014 month 2, day must be in range 1-28');

        JulianCalendarDate::create(-5014, 2, 29);
    }
}
