<?php

declare(strict_types=1);

namespace Arokettu\Date\Tests;

use Arokettu\Date\Calendar;
use Arokettu\Date\IsoWeekCalendar;
use Arokettu\Date\WeekDay;
use PHPUnit\Framework\TestCase;

class IsoWeekTest extends TestCase
{
    public function testDates(): void
    {
        $dates = [
            // from Wikipedia
            ['1977-01-01', '1976-W53-6'],
            ['1977-01-02', '1976-W53-7'],
            ['1977-12-31', '1977-W52-6'],
            ['1978-01-01', '1977-W52-7'],
            ['1978-01-02', '1978-W01-1'],
            ['1978-12-31', '1978-W52-7'],
            ['1979-01-01', '1979-W01-1'],
            ['1979-12-30', '1979-W52-7'],
            ['1979-12-31', '1980-W01-1'],
            ['1980-01-01', '1980-W01-2'],
            ['1980-12-28', '1980-W52-7'],
            ['1980-12-29', '1981-W01-1'],
            ['1980-12-30', '1981-W01-2'],
            ['1980-12-31', '1981-W01-3'],
            ['1981-01-01', '1981-W01-4'],
            ['1981-12-31', '1981-W53-4'],
            ['1982-01-01', '1981-W53-5'],
            ['1982-01-02', '1981-W53-6'],
            ['1982-01-03', '1981-W53-7'],
            // today
            ['2024-05-03', '2024-W18-5'],
        ];

        foreach ($dates as [$gregorian, $isoWeek]) {
            $date1 = Calendar::fromString($gregorian);
            self::assertEquals($isoWeek, $date1->isoWeek()->toString());

            $date2 = IsoWeekCalendar::fromString($isoWeek);
            self::assertEquals($gregorian, $date2->toString());

            self::assertEquals($date1, $date2);
        }
    }

    public function testGetters(): void
    {
        $date = IsoWeekCalendar::create(2024, 18, WeekDay::Friday);

        self::assertEquals(2024, $date->isoWeek()->getYear());
        self::assertEquals(18, $date->isoWeek()->getWeek());
        self::assertEquals(WeekDay::Friday, $date->isoWeek()->getWeekDay());
        self::assertEquals(5, $date->isoWeek()->getWeekDayNumber());
    }

    public function testParser(): void
    {
        $date = IsoWeekCalendar::create(2024, 18, WeekDay::Friday);
        $dateNeg = IsoWeekCalendar::create(-2024, 18, WeekDay::Friday);

        $date1 = IsoWeekCalendar::fromString('2024-W18-5');
        $date2 = IsoWeekCalendar::fromString('2024-18-5'); // W is optional
        $date3 = IsoWeekCalendar::fromString('-2024-W18-5'); // negative year is accepted
        $date4 = IsoWeekCalendar::fromString('2024W185'); // no dashes
        $date5 = IsoWeekCalendar::fromString('-2024W185'); // no dashes negative
        $date6 = IsoWeekCalendar::fromString('0000002024-W00000018-005'); // leading zeros
        $date7 = IsoWeekCalendar::fromString('00000002024W185'); // leading zeros for years

        self::assertEquals($date, $date1);
        self::assertEquals($date, $date2);
        self::assertEquals($dateNeg, $date3);
        self::assertEquals($date, $date4);
        self::assertEquals($dateNeg, $date5);
        self::assertEquals($date, $date6);
        self::assertEquals($date, $date7);
    }

    public function testParserShortWRequired(): void
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('Unable to parse the date string: "2024185"');

        IsoWeekCalendar::parse('2024185'); // W is required when no dashes
    }

    public function testParserShortWNoLeadingZeros(): void
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('Unable to parse the date string: "2024W001805"');

        IsoWeekCalendar::parse('2024W001805'); // no leading zeros after W
    }

    public function testRangeDayBelow(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Day must be an instance of WeekDay or an integer 1-7');

        IsoWeekCalendar::create(2024, 20, 0);
    }

    public function testRangeDayAbove(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Day must be an instance of WeekDay or an integer 1-7');

        IsoWeekCalendar::create(2024, 20, 8);
    }

    public function testRangeMonthBelow(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('For year 2024, week must be in range 1-52');

        IsoWeekCalendar::create(2024, 0, 5);
    }

    public function testRangeMonthAbove(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('For year 2024, week must be in range 1-52');

        IsoWeekCalendar::create(2024, 53, 5);
    }

    public function testRangeMonthAboveLeap(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('For year 2020, week must be in range 1-53');

        IsoWeekCalendar::create(2020, 54, 5);
    }

    public function testRangeMonthParser(): void
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage(
            'Unable to parse the date string: "2024-W54-5". For year 2024, week must be in range 1-52'
        );

        IsoWeekCalendar::parse('2024-W54-5');
    }

    public function testRangeMonthLeap(): void
    {
        $date = IsoWeekCalendar::create(2020, 53, 5);

        self::assertEquals('2021-01-01', (string)$date);
        self::assertEquals('2020-W53-5', (string)$date->isoWeek());
    }
}
