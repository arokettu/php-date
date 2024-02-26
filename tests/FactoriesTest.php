<?php

declare(strict_types=1);

namespace Arokettu\Date\Tests;

use Arokettu\Date\Date;
use Arokettu\Date\Month;
use PHPUnit\Framework\TestCase;

class FactoriesTest extends TestCase
{
    public function testToday(): void
    {
        retry:
        $sysDate1 = date('Y-m-d');
        $date = Date::today();
        $sysDate2 = date('Y-m-d');

        if ($sysDate1 !== $sysDate2) { // in case the date changes, do not let the test fail
            goto retry;
        }

        self::assertEquals($sysDate1, (string)$date);
    }

    public function testTodayInDifferentTz(): void
    {
        retry:
        // generate date in timezones where date is guaranteed to be different
        $sysDate1 = (new \DateTimeImmutable('today', new \DateTimeZone('-12:00')))->format('Y-m-d');
        $sysDate2 = (new \DateTimeImmutable('today', new \DateTimeZone('+12:00')))->format('Y-m-d');
        $date1 = Date::today(new \DateTimeZone('-12:00'));
        $date2 = Date::today(new \DateTimeZone('+12:00'));
        // guard against dt change during the test
        $sysDate3 = (new \DateTimeImmutable('today', new \DateTimeZone('-12:00')))->format('Y-m-d');

        if ($sysDate1 !== $sysDate3) { // in case the date changes, do not let the test fail
            goto retry;
        }

        self::assertEquals($sysDate1, (string)$date1);
        self::assertEquals($sysDate2, (string)$date2);

        self::assertEquals($date2, $date1->add(1));
    }

    public function testGregorianDate(): void
    {
        $date1 = Date::create(2024, 2, 25);
        $date2 = Date::create(2024, Month::February, 25); // use enum for a month

        self::assertEquals($date1, $date2);
        self::assertEquals('2024-02-25', (string)$date1);
    }

    public function testGregorianDateNegJD(): void
    {
        $date1 = Date::create(-5120, 5, 13);
        $date2 = Date::create(-5120, Month::May, 13); // use enum for a month

        self::assertEquals($date1, $date2);
        self::assertEquals('-5120-05-13', (string)$date1);
    }

    public function testGregorianDateWrongMonth(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Month must be an instance of Month or an integer 1-12');

        Date::create(2000, 13, 13);
    }

    public function testGregorianDateWrongDay(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('For year 2000 month 11, day must be in range 1-30');

        Date::create(2000, 11, 33);
    }

    public function testParser(): void
    {
        // positive year
        $date = Date::parse('2025-2-25');
        self::assertEquals('2025-02-25', (string)$date);

        // negative year
        $date = Date::parse('-2025-2-25');
        self::assertEquals('-2025-02-25', (string)$date);

        // zero year
        $date = Date::parse('0-2-25');
        self::assertEquals('0-02-25', (string)$date);
        $date = Date::parse('-0-2-25');
        self::assertEquals('0-02-25', (string)$date);
    }

    public function testParserInvalidFormat(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Unable to parse the date string: 2015/12/12');

        Date::parse('2015/12/12'); // Only Y-m-d is accepted
    }

    public function testParserInvalidValue(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('For year 2015 month 2, day must be in range 1-28');

        Date::parse('2015-002-42');
    }
}
