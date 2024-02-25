<?php

declare(strict_types=1);

namespace Arokettu\Date\Tests;

use Arokettu\Date\Date;
use Arokettu\Date\WeekDay;
use PHPUnit\Framework\TestCase;

class RangeEdgeCasesTest extends TestCase
{
    public function testZero(): void
    {
        $date1 = new Date(0);
        self::assertEquals('-4713-11-24', (string)$date1); // Jan 1, 4713 BCE (-4712) Julian
        self::assertEquals(WeekDay::Monday, $date1->getWeekDay());

        $date2 = Date::create(-4713, 11, 24);
        self::assertEquals($date1, $date2);
    }

    public function testPhpIntMax64(): void
    {
        if (PHP_INT_SIZE < 8) {
            $this->markTestSkipped();
        }

        $date1 = new Date(9223372036854775807);
        self::assertEquals('25252734927761842-06-20', (string)$date1);
        self::assertEquals(WeekDay::Monday, $date1->getWeekDay());

        $date2 = Date::create(25252734927761842, 6, 20);
        self::assertEquals($date1, $date2);
    }

    public function testPhpIntMin64(): void
    {
        if (PHP_INT_SIZE < 8) {
            $this->markTestSkipped();
        }

        $date1 = new Date(-9223372036854775807-1);
        self::assertEquals('-25252734927771267-04-30', (string)$date1);
        self::assertEquals(WeekDay::Sunday, $date1->getWeekDay());

//        $date2 = Date::create(-25252734927771267, 5, 1);
//        self::assertEquals($date1, $date2);
    }

    public function testPhpIntMin32(): void
    {
        $date1 = new Date(2147483647);
        self::assertEquals('5874898-06-03', (string)$date1);
        self::assertEquals(WeekDay::Tuesday, $date1->getWeekDay());

        $date2 = Date::create(5874898, 6, 3);
        self::assertEquals($date1, $date2);
    }
}
