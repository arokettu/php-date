<?php

declare(strict_types=1);

namespace Arokettu\Date\Tests;

use Arokettu\Date\Date;
use PHPUnit\Framework\TestCase;

class MagicMethodsTest extends TestCase
{
    public function testSerialize(): void
    {
        $date = new Date(1234567);

        self::assertEquals([1234567], $date->__serialize());
        self::assertEquals($date, unserialize(serialize($date)));
    }

    public function testDebugInfo(): void
    {
        $date = new Date(2345678);

        self::assertEquals([
            'julianDay' => 2345678,
            'gregorian' => '1710-02-23',
            'julian' => '1710-02-12',
            'milankovic' => '1710-02-23',
            'weekDay' => 'Sunday',
            'isoWeek' => '1710-W08-7',
        ], $date->__debugInfo());
    }

    public function testToString(): void
    {
        $date1 = new Date(2345678);
        self::assertEquals('1710-02-23', (string)$date1);

        $date2 = new Date(-2345678);
        self::assertEquals('-11135-08-25', (string)$date2);
    }
}
