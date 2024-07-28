<?php

declare(strict_types=1);

namespace Arokettu\Date\Tests;

use Arokettu\Date\Calendar;
use Arokettu\Date\Date;
use Arokettu\Date\JulianCalendar;
use Arokettu\Date\Month;
use PHPUnit\Framework\TestCase;

class JulianCalendarTest extends TestCase
{
    public function testGetters(): void
    {
        $date = JulianCalendar::parse('2024-03-16');

        self::assertEquals(Calendar::parse('2024-03-29'), $date);
        self::assertEquals(16, $date->julian()->getDay());
        self::assertEquals(Month::March, $date->julian()->getMonth());
        self::assertEquals(3, $date->julian()->getMonthNumber());
        self::assertEquals(2024, $date->julian()->getYear());
    }

    public function testFactories(): void
    {
        $date1 = JulianCalendar::parse('2024-03-16');
        $date2 = JulianCalendar::create(2024, 3, 16);
        $date3 = JulianCalendar::create(2024, Month::March, 16);

        self::assertEquals($date1, $date2);
        self::assertEquals($date1, $date3);
    }

    public function testSameInstance(): void
    {
        $date = Date::today();

        self::assertTrue($date->julian() === $date->julian());
    }

    public function testArithmetic(): void
    {
        $date1 = JulianCalendar::parse('2100-01-01');
        $date2 = JulianCalendar::parse('2100-04-10');

        self::assertEquals($date2, $date1->add(100));
        self::assertEquals($date1, $date2->subDays(100));
        self::assertEquals(100, $date2->sub($date1));
    }

    public function testChristmas(): void
    {
        // 21st century
        $christmas = JulianCalendar::parse('2023-12-25');
        self::assertEquals('2024-01-07', $christmas->toString());
        self::assertEquals('2023-12-25', $christmas->julian()->toString());

        // 18th century
        $christmas = JulianCalendar::parse('1823-12-25');
        self::assertEquals('1824-01-06', $christmas->toString()); // a day less difference
        self::assertEquals('1823-12-25', $christmas->julian()->toString());

        // 23rd century
        $christmas = JulianCalendar::parse('2223-12-25');
        self::assertEquals('2224-01-09', $christmas->toString()); // 2 days more difference
        self::assertEquals('2223-12-25', $christmas->julian()->toString());
    }

    public function testLeapYears(): void
    {
        // non leap
        $date = JulianCalendar::create(2014, 2, 28);
        self::assertEquals('2014-03-01', (string)$date->add(1)->julian());
        // negative non leap
        $date = JulianCalendar::create(-5014, 2, 28);
        self::assertEquals('-5014-03-01', (string)$date->add(1)->julian());

        // leap
        $date = JulianCalendar::create(2016, 2, 28);
        self::assertEquals('2016-02-29', (string)$date->add(1)->julian());
        // negative leap
        $date = JulianCalendar::create(-5016, 2, 28);
        self::assertEquals('-5016-02-29', (string)$date->add(1)->julian());

        // non leap in gregorian, leap in julian
        $date = JulianCalendar::create(1900, 2, 28);
        self::assertEquals('1900-02-29', (string)$date->add(1)->julian());
        // negative non leap in gregorian, leap in julian
        $date = JulianCalendar::create(-5000, 2, 28);
        self::assertEquals('-5000-02-29', (string)$date->add(1)->julian());

        // leap century
        $date = JulianCalendar::create(2400, 2, 28);
        self::assertEquals('2400-02-29', (string)$date->add(1)->julian());
        // negative leap century
        $date = JulianCalendar::create(-5200, 2, 28);
        self::assertEquals('-5200-02-29', (string)$date->add(1)->julian());
    }

    public function testCreateLeapYear(): void
    {
        // leap
        $date = JulianCalendar::create(2016, 2, 29);
        self::assertEquals('2016-02-29', (string)$date->julian());
        // negative leap
        $date = JulianCalendar::create(-5016, 2, 29);
        self::assertEquals('-5016-02-29', (string)$date->julian());

        // leap century
        $date = JulianCalendar::create(2100, 2, 29);
        self::assertEquals('2100-02-29', (string)$date->julian());
        // negative leap century
        $date = JulianCalendar::create(-5100, 2, 29);
        self::assertEquals('-5100-02-29', (string)$date->julian());
    }

    public function testCreateLeapYearNonLeap(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('For year 2014 month 2, day must be in range 1-28');

        JulianCalendar::create(2014, 2, 29);
    }

    public function testCreateLeapYearNonLeapNeg(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('For year -5014 month 2, day must be in range 1-28');

        JulianCalendar::create(-5014, 2, 29);
    }

    public function testParserInvalidFormat(): void
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('Unable to parse the date string: "2015/12/12"');

        JulianCalendar::parse('2015/12/12'); // Only Y-m-d is accepted
    }

    public function testParserInvalidValue(): void
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage(
            'Unable to parse the date string: "2015-002-42". For year 2015 month 2, day must be in range 1-28'
        );

        JulianCalendar::parse('2015-002-42');
    }

    public function testDateWrongMonth(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Month must be an instance of Month or an integer 1-12');

        JulianCalendar::create(2000, 13, 13);
    }

    public function testDateWrongDay(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('For year 2000 month 11, day must be in range 1-30');

        JulianCalendar::create(2000, 11, 33);
    }
}
