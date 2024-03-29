<?php

declare(strict_types=1);

namespace Arokettu\Date\Calendars;

use Stringable;

final readonly class JulianCalendarDate implements Stringable
{
    use GregorianLikeDate;

    public function getDateArray(): array
    {
        if (isset($this->dateArray)) {
            return $this->dateArray;
        }

        $j = $this->date->julianDay;

        // normalize to 0-700 years (255675 days)
        $c = intdiv($j, 255675);
        $j -= $c * 255675;
        // additional step to avoid int overflow on PHP_INT_MIN
        if ($j < 0) {
            $j += 255675;
            $c -= 1;
        }

        // https://en.wikipedia.org/wiki/Julian_day#Julian_or_Gregorian_calendar_from_Julian_day_number
        $f = $j + 1401;
        $e = 4 * $f + 3;
        $g = intdiv($e % 1461, 4);
        $h = 5 * $g + 2;

        $d = intdiv($h % 153, 5) + 1;
        $m = (intdiv($h, 153) + 2) % 12 + 1;
        $y = intdiv($e, 1461) - 4716 + intdiv(12 + 2 - $m, 12);

        return $this->dateArray = [$y + $c * 700, $m, $d];
    }
}
