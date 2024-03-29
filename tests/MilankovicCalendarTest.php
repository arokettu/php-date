<?php

declare(strict_types=1);

namespace Arokettu\Date\Tests;

use Arokettu\Date\Calendar;
use Arokettu\Date\Date;
use Arokettu\Date\MilankovicCalendar;
use Arokettu\Date\Month;
use PHPUnit\Framework\TestCase;

class MilankovicCalendarTest extends TestCase
{
    public function testGetters(): void
    {
        $date = MilankovicCalendar::parse('2024-03-16');

        self::assertEquals(Calendar::parse('2024-03-16'), $date);
        self::assertEquals(16, $date->milankovic()->getDay());
        self::assertEquals(Month::March, $date->milankovic()->getMonth());
        self::assertEquals(3, $date->milankovic()->getMonthNumber());
        self::assertEquals(2024, $date->milankovic()->getYear());
    }

    public function testFactories(): void
    {
        $date1 = MilankovicCalendar::parse('2024-03-16');
        $date2 = MilankovicCalendar::create(2024, 3, 16);
        $date3 = MilankovicCalendar::create(2024, Month::March, 16);

        self::assertEquals($date1, $date2);
        self::assertEquals($date1, $date3);
    }

    public function testSpecificEdgeCase(): void
    {
        $date = MilankovicCalendar::parse('1800-01-01'); // year is divisible by 900 and month is before March

        self::assertEquals(Calendar::parse('1800-01-01'), $date); // in 19th century they align
    }

    public function testSameInstance(): void
    {
        $date = Date::today();

        self::assertTrue($date->milankovic() === $date->milankovic());
    }

    public function testArithmetic(): void
    {
        $date1 = MilankovicCalendar::parse('2000-01-01');
        $date2 = MilankovicCalendar::parse('2000-04-10');

        self::assertEquals($date2, $date1->add(100));
        self::assertEquals($date1, $date2->subDays(100));
        self::assertEquals(100, $date2->sub($date1));
    }

    public function testChristmas(): void
    {
        // 21st century
        $christmas = MilankovicCalendar::parse('2023-12-25');
        self::assertEquals('2023-12-25', $christmas->toString());
        self::assertEquals('2023-12-25', $christmas->milankovic()->toString());

        // 12th century
        $christmas = MilankovicCalendar::parse('1123-12-25');
        self::assertEquals('1123-12-26', $christmas->toString()); // a day less
        self::assertEquals('1123-12-25', $christmas->milankovic()->toString());

        // 29th century
        $christmas = MilankovicCalendar::parse('2823-12-25');
        self::assertEquals('2823-12-24', $christmas->toString()); // a day more
        self::assertEquals('2823-12-25', $christmas->milankovic()->toString());
    }

    public function testLeapYears(): void
    {
        // non leap
        $date = MilankovicCalendar::create(2014, 2, 28);
        self::assertEquals('2014-03-01', (string)$date->add(1)->milankovic());
        // negative non leap
        $date = MilankovicCalendar::create(-5014, 2, 28);
        self::assertEquals('-5014-03-01', (string)$date->add(1)->milankovic());

        // leap
        $date = MilankovicCalendar::create(2016, 2, 28);
        self::assertEquals('2016-02-29', (string)$date->add(1)->milankovic());
        // negative leap
        $date = MilankovicCalendar::create(-5016, 2, 28);
        self::assertEquals('-5016-02-29', (string)$date->add(1)->milankovic());

        // leap century
        $date = MilankovicCalendar::create(2000, 2, 28);
        self::assertEquals('2000-02-29', (string)$date->add(1)->milankovic());
        // negative leap century
        $date = MilankovicCalendar::create(-4800, 2, 28);
        self::assertEquals('-4800-02-29', (string)$date->add(1)->milankovic());

        // non leap in gregorian, leap in milankovic
        $date = MilankovicCalendar::create(2900, 2, 28);
        self::assertEquals('2900-02-29', (string)$date->add(1)->milankovic());
        // negative non leap in gregorian, leap in milankovic
        $date = MilankovicCalendar::create(-6600, 2, 28);
        self::assertEquals('-6600-02-29', (string)$date->add(1)->milankovic());

        // leap in gregorian, non leap in milankovic
        $date = MilankovicCalendar::create(2800, 2, 28);
        self::assertEquals('2800-03-01', (string)$date->add(1)->milankovic());
        // negative leap in gregorian, non leap in milankovic
        $date = MilankovicCalendar::create(-5600, 2, 28);
        self::assertEquals('-5600-03-01', (string)$date->add(1)->milankovic());
    }

    public function testCreateLeapYear(): void
    {
        // leap
        $date = MilankovicCalendar::create(2016, 2, 29);
        self::assertEquals('2016-02-29', (string)$date->milankovic());
        // negative leap
        $date = MilankovicCalendar::create(-5008, 2, 29);
        self::assertEquals('-5008-02-29', (string)$date->milankovic());

        // leap century
        $date = MilankovicCalendar::create(2900, 2, 29);
        self::assertEquals('2900-02-29', (string)$date->milankovic());
        // negative leap century
        $date = MilankovicCalendar::create(-6600, 2, 29);
        self::assertEquals('-6600-02-29', (string)$date->milankovic());
    }

    public function testCreateLeapYearNonLeap(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('For year 2014 month 2, day must be in range 1-28');

        MilankovicCalendar::create(2014, 2, 29);
    }

    public function testCreateLeapYearNonLeapNeg(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('For year -5014 month 2, day must be in range 1-28');

        MilankovicCalendar::create(-5014, 2, 29);
    }

    public function testCreateLeapYearNonLeapCentury(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('For year 1900 month 2, day must be in range 1-28');

        MilankovicCalendar::create(1900, 2, 29);
    }

    public function testCreateLeapYearNonLeapCenturyNeg(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('For year -5000 month 2, day must be in range 1-28');

        MilankovicCalendar::create(-5000, 2, 29);
    }

    public function testParserInvalidFormat(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Unable to parse the date string: 2015/12/12');

        MilankovicCalendar::parse('2015/12/12'); // Only Y-m-d is accepted
    }

    public function testParserInvalidValue(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('For year 2015 month 2, day must be in range 1-28');

        MilankovicCalendar::parse('2015-002-42');
    }

    public function testDateWrongMonth(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Month must be an instance of Month or an integer 1-12');

        MilankovicCalendar::create(2000, 13, 13);
    }

    public function testDateWrongDay(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('For year 2000 month 11, day must be in range 1-30');

        MilankovicCalendar::create(2000, 11, 33);
    }
}
