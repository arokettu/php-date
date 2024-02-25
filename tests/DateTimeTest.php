<?php

declare(strict_types=1);

namespace Arokettu\Date\Tests;

use Arokettu\Date\Date;
use PHPUnit\Framework\TestCase;

class DateTimeTest extends TestCase
{
    public function testFromDateTime(): void
    {
        $dt = new \DateTime('2024-02-25');
        $date = Date::fromDateTime($dt);

        self::assertEquals(2460366, $date->julianDay);
    }

    public function testToDateTime(): void
    {
        $dt = new \DateTime('2024-02-25');
        $date = new Date(2460366);

        self::assertEquals($dt, $date->toDateTime());
    }
}
