<?php

declare(strict_types=1);

namespace Arokettu\Date\Calendars;

use Stringable;

final readonly class MilankovicDate implements Stringable
{
    use GregorianLikeDate;

    private const Y900_DAYS = 328718;
    private const Y900_YEARS = 900;
    private const BASE_DAY = 1721119; // 0-2-28 Milankovic

    public function getDateArray(): array
    {
        if (isset($this->dateArray)) {
            return $this->dateArray;
        }

        $j = $this->date->julianDay;

        // normalize to 0-900 years (328718 days)
        $c = intdiv($j, self::Y900_DAYS) - 7;
        if ($c >= 0) {
            $j -= $c * self::Y900_DAYS;
        } else {
            // prevent int_min overflow
            $c1 = intdiv($c,2);
            $c2 = $c - $c1;
            $j -= $c1 * self::Y900_DAYS;
            $j -= $c2 * self::Y900_DAYS;
        }

        $d = 9 * ($j - self::BASE_DAY - 1) + 2;
        $e = intdiv($d, self::Y900_DAYS);
        $dd = 100 * intdiv($d % self::Y900_DAYS, 9) + 99;
        $yy = intdiv($dd, 36525);
        $yd = 5 * intdiv($dd % 36525, 100) + 2;
        $mm = intdiv($yd, 153);
        $mc = intdiv($mm + 2, 12);

        $y = 100 * $e + $yy + $mc;
        $m = $mm - 12 * $mc + 3;
        $d = intdiv($yd % 153, 5) + 1;

        return [$y + $c * 900, $m, $d];
    }
}
