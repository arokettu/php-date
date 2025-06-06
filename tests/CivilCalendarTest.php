<?php

declare(strict_types=1);

namespace Arokettu\Date\Tests;

use Arokettu\Date\Calendar;
use Arokettu\Date\CivilCalendar;
use Arokettu\Date\Date;
use Arokettu\Date\Month;
use DomainException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use RangeException;
use UnexpectedValueException;

class CivilCalendarTest extends TestCase
{
    public static function skipProvider(): array
    {
        return [
            ['italy',       CivilCalendar::ITALY,       '1582-10-04', '1582-10-15'],
            ['hungary',     CivilCalendar::HUNGARY,     '1587-10-21', '1587-11-01'],
            ['denmark',     CivilCalendar::DENMARK,     '1700-02-18', '1700-03-01'],
            ['britain',     CivilCalendar::BRITAIN,     '1752-09-02', '1752-09-14'],
            ['sweden',      CivilCalendar::SWEDEN,      '1753-02-17', '1753-03-01'],
            ['albania',     CivilCalendar::ALBANIA,     '1912-11-14', '1912-11-28'],
            ['bulgaria',    CivilCalendar::BULGARIA,    '1916-03-31', '1916-04-14'],
            ['russia',      CivilCalendar::RUSSIA,      '1918-01-31', '1918-02-14'],
            ['estonia',     CivilCalendar::ESTONIA,     '1918-02-15', '1918-03-01'],
            ['yugolsavia',  CivilCalendar::YUGOSLAVIA,  '1919-01-14', '1919-01-28'],
            ['greece',      CivilCalendar::GREECE,      '1923-02-15', '1923-03-01'],
        ];
    }

    #[DataProvider('skipProvider')]
    public function testSkip(string $label, int $calendar, string $preSkip, string $postSkip): void
    {
        self::assertEquals(
            $postSkip,
            Date::createFromJulianDay($calendar)
                ->civil($calendar)->toString(),
            $label . ' post skip',
        );
        self::assertEquals(
            $preSkip,
            Date::createFromJulianDay($calendar - 1)
                ->civil($calendar)->toString(),
            $label . ' pre skip',
        );
    }

    #[DataProvider('skipProvider')]
    public function testSkipWithObject(string $label, int $calendar, string $preSkip, string $postSkip): void
    {
        self::assertEquals(
            $postSkip,
            Date::createFromJulianDay($calendar)
                ->civil(new Date($calendar))->toString(),
            $label . ' post skip',
        );
        self::assertEquals(
            $preSkip,
            Date::createFromJulianDay($calendar - 1)
                ->civil(new Date($calendar))->toString(),
            $label . ' pre skip',
        );
    }

    #[DataProvider('skipProvider')]
    public function testSkipWithCalendar(string $label, int $calendar, string $preSkip, string $postSkip): void
    {
        $civil = CivilCalendar::for($calendar);

        self::assertEquals(
            $postSkip,
            $civil->dateToString(Date::createFromJulianDay($calendar)),
            $label . ' post skip',
        );
        self::assertEquals(
            $preSkip,
            (string)$civil->civilDate(Date::createFromJulianDay($calendar - 1)),
            $label . ' pre skip',
        );
    }

    public function testSkipBelowMin(): void
    {
        self::expectException(DomainException::class);
        self::expectExceptionMessage(
            'Switch day cannot be earlier than "200-03-01", "122-01-01" (Julian day 1765620) given'
        );

        Date::today()->civil(Calendar::parse('122-01-01'));
    }

    public function testCalendarBelowMin(): void
    {
        self::expectException(DomainException::class);
        self::expectExceptionMessage(
            'Switch day cannot be earlier than "200-03-01", "111-11-11" (Julian day 1761916) given'
        );

        CivilCalendar::for(Calendar::parse('111-11-11'));
    }

    public function testParse(): void
    {
        $date = '1868-01-03';
        $dateGregorian = new Date(2_403_335);
        $dateJulian = new Date(2_403_347);

        self::assertEquals($dateGregorian, CivilCalendar::for(CivilCalendar::BRITAIN)->parse($date));
        self::assertEquals($dateJulian, CivilCalendar::for(new Date(CivilCalendar::RUSSIA))->parse($date));
    }

    #[DataProvider('skipProvider')]
    public function testParseNearSkip(string $label, int $calendar, string $preSkip, string $postSkip): void
    {
        self::assertEquals(
            new Date($calendar - 1),
            CivilCalendar::for($calendar)->parse($preSkip),
            $label . ' pre skip'
        );
        self::assertEquals(
            new Date($calendar),
            CivilCalendar::for($calendar)->parse($postSkip),
            $label . ' post skip'
        );
    }

    public function testPhpIntMax64(): void
    {
        if (PHP_INT_SIZE < 8) {
            $this->markTestSkipped();
        }

        // from Gregorian edge cases
        $date1 = CivilCalendar::for(CivilCalendar::HUNGARY)->parse('25252734927761842-06-20');
        $date2 = new Date(9223372036854775807);

        self::assertEquals($date2, $date1);
    }

    public function testPhpIntMin64(): void
    {
        if (PHP_INT_SIZE < 8) {
            $this->markTestSkipped();
        }

        // from Julian edge cases
        $date1 = CivilCalendar::for(CivilCalendar::HUNGARY)->parse('-25252216391119773-08-11');
        $date2 = new Date(-9223372036854775807 - 1);

        self::assertEquals($date2, $date1);
    }

    public function testSkippedDate(): void
    {
        self::expectException(DomainException::class);
        self::expectExceptionMessage(
            '"1916-04-10" likely belongs to the switch gap. Dates between "1916-03-31" and "1916-04-14" are invalid'
        );

        CivilCalendar::for(CivilCalendar::BULGARIA)->create(1916, Month::April, 10);
    }

    public function testSkippedDateInParser(): void
    {
        self::expectException(UnexpectedValueException::class);
        self::expectExceptionMessage(
            '"1916-04-10" likely belongs to the switch gap. Dates between "1916-03-31" and "1916-04-14" are invalid'
        );

        CivilCalendar::for(CivilCalendar::BULGARIA)->parse('1916-04-10');
    }

    public function testParserInvalidFormat(): void
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Unable to parse the date string: "2015/12/12"');

        CivilCalendar::for(CivilCalendar::GREECE)->parse('2015/12/12'); // Only Y-m-d is accepted
    }

    public function testOverflow(): void
    {
        self::expectException(RangeException::class);
        self::expectExceptionMessage('Date value overflow');

        CivilCalendar::for(CivilCalendar::MIN)->create(PHP_INT_MAX, Month::June, 12);
    }

    public function testInvalidGregorian(): void
    {
        $calendar = CivilCalendar::for(CivilCalendar::ESTONIA);

        self::expectException(DomainException::class);
        self::expectExceptionMessage(
            'Unable to parse the due to errors: ' .
            'Gregorian: "For year 2100 month 2, day must be in range 1-28", ' .
            'Julian: "Julian is not applicable because the date is on or after the switch date"'
        );

        // valid julian but invalid gregorian
        $calendar->create(2100, 2, 29);
    }

    public function testInvalidDate(): void
    {
        $calendar = CivilCalendar::for(CivilCalendar::ESTONIA);

        self::expectException(DomainException::class);
        self::expectExceptionMessage(
            'Unable to parse the due to errors: ' .
            'Gregorian: "For year 2100 month 2, day must be in range 1-28", ' .
            'Julian: "For year 2100 month 2, day must be in range 1-29"'
        );

        // valid julian but invalid gregorian
        $calendar->create(2100, 2, 31);
    }
}
