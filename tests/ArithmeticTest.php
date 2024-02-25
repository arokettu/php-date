<?php

declare(strict_types=1);

namespace Arokettu\Date\Tests;

use Arokettu\Date\Date;
use PHPUnit\Framework\TestCase;

class ArithmeticTest extends TestCase
{
    public function testAdd(): void
    {
        $date = Date::create(2024, 2, 25);
        $date = $date->add(100);

        self::assertEquals('2024-06-04', (string)$date);
    }

    public function testSubDays(): void
    {
        $date = Date::create(2024, 2, 25);
        $date1 = $date->subDays(100);
        $date2 = $date->add(-100); // equivalent

        self::assertEquals('2023-11-17', (string)$date1);
        self::assertEquals($date1, $date2);
    }

    public function testSub(): void
    {
        $date1 = Date::create(2024, 2, 25);
        $date2 = Date::create(2024, 6, 4);

        self::assertEquals(100, $date2->sub($date1));
    }
}
