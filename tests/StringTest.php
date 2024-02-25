<?php

declare(strict_types=1);

namespace Arokettu\Date\Tests;

use Arokettu\Date\Date;
use PHPUnit\Framework\TestCase;

class StringTest extends TestCase
{
    public function testToString(): void
    {
        self::assertEquals('2024-02-25', (string) new Date(2460366));
    }
}
