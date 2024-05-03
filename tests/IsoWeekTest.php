<?php

declare(strict_types=1);

namespace Arokettu\Date\Tests;

use Arokettu\Date\Calendar;
use Arokettu\Date\IsoWeekCalendar;
use PHPUnit\Framework\TestCase;

class IsoWeekTest extends TestCase
{
    public function testDates(): void
    {
        $dates = [
            // from Wikipedia
            ['1977-01-01', '1976-W53-6'],
            ['1977-01-02', '1976-W53-7'],
            ['1977-12-31', '1977-W52-6'],
            ['1978-01-01', '1977-W52-7'],
            ['1978-01-02', '1978-W01-1'],
            ['1978-12-31', '1978-W52-7'],
            ['1979-01-01', '1979-W01-1'],
            ['1979-12-30', '1979-W52-7'],
            ['1979-12-31', '1980-W01-1'],
            ['1980-01-01', '1980-W01-2'],
            ['1980-12-28', '1980-W52-7'],
            ['1980-12-29', '1981-W01-1'],
            ['1980-12-30', '1981-W01-2'],
            ['1980-12-31', '1981-W01-3'],
            ['1981-01-01', '1981-W01-4'],
            ['1981-12-31', '1981-W53-4'],
            ['1982-01-01', '1981-W53-5'],
            ['1982-01-02', '1981-W53-6'],
            ['1982-01-03', '1981-W53-7'],
            // today
            ['2024-05-03', '2024-W18-5'],
        ];

        foreach ($dates as [$gregorian, $isoWeek]) {
            $date1 = Calendar::fromString($gregorian);
            self::assertEquals($isoWeek, $date1->isoWeek()->toString());

            $date2 = IsoWeekCalendar::fromString($isoWeek);
            self::assertEquals($gregorian, $date2->toString());

            self::assertEquals($date1, $date2);
        }
    }
}
