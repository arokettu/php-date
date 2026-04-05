<?php

/**
 * @copyright 2024 Anton Smirnov
 * @license MIT https://spdx.org/licenses/MIT.html
 */

declare(strict_types=1);

namespace Arokettu\Date\Tests;

use Arokettu\Date\Calendar;
use Arokettu\Date\Date;
use Arokettu\Date\Easter;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class EasterTest extends TestCase
{
    #[DataProvider('easter20thCentury')]
    public function testGregorian(int $year, Date $date): void
    {
        self::assertEquals($date, Easter::gregorian($year), (string)$date);
    }

    public static function easter20thCentury(): iterable
    {
        $f = fopen(__DIR__ . '/data/easter20cen.csv', 'r');
        fgets($f); // skip header
        while (($line = fgetcsv($f, escape: '')) !== false) {
            yield [(int)$line[0], Calendar::parse($line[1])];
        }
    }

    #[DataProvider('orthodoxEaster20thCentury')]
    public function testJulian(int $year, Date $date): void
    {
        self::assertEquals($date, Easter::julian($year), (string)$date);
    }

    public static function orthodoxEaster20thCentury(): iterable
    {
        $f = fopen(__DIR__ . '/data/easter20cen.csv', 'r');
        fgets($f); // skip header
        while (($line = fgetcsv($f, escape: '')) !== false) {
            yield [(int)$line[0], Calendar::parse($line[2])];
        }
    }
}
