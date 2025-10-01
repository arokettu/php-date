<?php

/**
 * @copyright 2024 Anton Smirnov
 * @license MIT https://spdx.org/licenses/MIT.html
 */

declare(strict_types=1);

namespace Arokettu\Date\Tests;

use Arokettu\Date\Date;
use PHPUnit\Framework\TestCase;

final class StringTest extends TestCase
{
    public function testToString(): void
    {
        self::assertEquals('2024-02-25', (string) new Date(2460366));
    }
}
