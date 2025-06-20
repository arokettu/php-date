<?php

declare(strict_types=1);

namespace Arokettu\Date\Tests;

use Arokettu\Date\Calendar;
use Arokettu\Date\Month;
use PHPUnit\Framework\TestCase;

class ArithmeticTest extends TestCase
{
    public function testAdd(): void
    {
        $date = Calendar::create(2024, 2, 25);
        $date = $date->add(100);

        self::assertEquals('2024-06-04', (string)$date);
    }

    public function testSubDays(): void
    {
        $date = Calendar::create(2024, 2, 25);
        $date1 = $date->subDays(100);
        $date2 = $date->add(-100); // equivalent

        self::assertEquals('2023-11-17', (string)$date1);
        self::assertEquals($date1, $date2);
    }

    public function testSub(): void
    {
        $date1 = Calendar::create(2024, 2, 25);
        $date2 = Calendar::create(2024, 6, 4);

        self::assertEquals(100, $date2->sub($date1));
    }

    public function testCompare(): void
    {
        $date1 = Calendar::create(2025, 6, 13);
        $date2 = Calendar::create(2025, Month::June, 13);
        $date3 = Calendar::create(2025, 1, 13);
        $date4 = Calendar::create(2025, 11, 13);

        self::assertEquals(0, $date1->compare($date2));
        self::assertEquals(1, $date1->compare($date3));
        self::assertEquals(-1, $date1->compare($date4));
    }
}
