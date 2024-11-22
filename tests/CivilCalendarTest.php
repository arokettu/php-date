<?php

declare(strict_types=1);

namespace Arokettu\Date\Tests;

use Arokettu\Date\CivilCalendar;
use Arokettu\Date\Date;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class CivilCalendarTest extends TestCase
{
    public static function skipProvider(): array
    {
        return [
            ['italy',       CivilCalendar::ITALY,       '1582-10-04', '1582-10-15'],
            ['hungary',     CivilCalendar::HUNGARY,     '1587-10-21', '1587-11-01'],
            ['denmark',     CivilCalendar::DENMARK,     '1700-02-18', '1700-03-01'],
            ['britain',     CivilCalendar::BRITAIN,     '1752-09-02', '1752-09-14'],
            ['sweden',      CivilCalendar::SWEDEN,      '1753-02-17', '1753-03-01'],
            ['albania',     CivilCalendar::ALBANIA,     '1912-11-14', '1912-11-28'],
            ['bulgaria',    CivilCalendar::BULGARIA,    '1916-03-31', '1916-04-14'],
            ['russia',      CivilCalendar::RUSSIA,      '1918-01-31', '1918-02-14'],
            ['estonia',     CivilCalendar::ESTONIA,     '1918-02-15', '1918-03-01'],
            ['yugolsavia',  CivilCalendar::YUGOSLAVIA,  '1919-01-14', '1919-01-28'],
            ['greece',      CivilCalendar::GREECE,      '1923-02-15', '1923-03-01'],
        ];
    }

    #[DataProvider('skipProvider')]
    public function testSkip(string $label, int $calendar, string $preSkip, string $postSkip): void
    {
        self::assertEquals(
            $postSkip,
            Date::createFromJulianDay($calendar)
                ->civil($calendar)->toString(),
            $label . ' post skip',
        );
        self::assertEquals(
            $preSkip,
            Date::createFromJulianDay($calendar - 1)
                ->civil($calendar)->toString(),
            $label . ' pre skip',
        );
    }
}
