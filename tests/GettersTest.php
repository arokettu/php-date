<?php

declare(strict_types=1);

namespace Arokettu\Date\Tests;

use Arokettu\Date\Date;
use Arokettu\Date\WeekDay;
use PHPUnit\Framework\TestCase;

class GettersTest extends TestCase
{
    public function testWeekDay(): void
    {
        $date = new Date(2460366);
        self::assertEquals(WeekDay::Sunday, $date->getWeekDay());
    }
}
