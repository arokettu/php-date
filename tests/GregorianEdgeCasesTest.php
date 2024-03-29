<?php

declare(strict_types=1);

namespace Arokettu\Date\Tests;

use Arokettu\Date\Calendar;
use PHPUnit\Framework\TestCase;

class GregorianEdgeCasesTest extends TestCase
{
    public function testLeapYears(): void
    {
        // non leap
        $date = Calendar::create(2014, 2, 28);
        self::assertEquals('2014-03-01', (string)$date->add(1));
        // negative non leap
        $date = Calendar::create(-5014, 2, 28);
        self::assertEquals('-5014-03-01', (string)$date->add(1));

        // leap
        $date = Calendar::create(2016, 2, 28);
        self::assertEquals('2016-02-29', (string)$date->add(1));
        // negative leap
        $date = Calendar::create(-5016, 2, 28);
        self::assertEquals('-5016-02-29', (string)$date->add(1));

        // non leap century
        $date = Calendar::create(1900, 2, 28);
        self::assertEquals('1900-03-01', (string)$date->add(1));
        // negative non leap century
        $date = Calendar::create(-5000, 2, 28);
        self::assertEquals('-5000-03-01', (string)$date->add(1));

        // leap century
        $date = Calendar::create(2400, 2, 28);
        self::assertEquals('2400-02-29', (string)$date->add(1));
        // negative leap century
        $date = Calendar::create(-5200, 2, 28);
        self::assertEquals('-5200-02-29', (string)$date->add(1));
    }

    public function testCreateLeapYear(): void
    {
        // leap
        $date = Calendar::create(2016, 2, 29);
        self::assertEquals('2016-02-29', (string)$date);
        // negative leap
        $date = Calendar::create(-5016, 2, 29);
        self::assertEquals('-5016-02-29', (string)$date);

        // leap century
        $date = Calendar::create(2400, 2, 29);
        self::assertEquals('2400-02-29', (string)$date);
        // negative leap century
        $date = Calendar::create(-5200, 2, 29);
        self::assertEquals('-5200-02-29', (string)$date);
    }

    public function testCreateLeapYearNonLeap(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('For year 2014 month 2, day must be in range 1-28');

        Calendar::create(2014, 2, 29);
    }

    public function testCreateLeapYearNonLeapNeg(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('For year -5014 month 2, day must be in range 1-28');

        Calendar::create(-5014, 2, 29);
    }

    public function testCreateLeapYearNonLeapCentury(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('For year 1900 month 2, day must be in range 1-28');

        Calendar::create(1900, 2, 29);
    }

    public function testCreateLeapYearNonLeapCenturyNeg(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('For year -5000 month 2, day must be in range 1-28');

        Calendar::create(-5000, 2, 29);
    }
}
