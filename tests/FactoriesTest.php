<?php

declare(strict_types=1);

namespace Arokettu\Date\Tests;

use Arokettu\Date\Date;
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
}
