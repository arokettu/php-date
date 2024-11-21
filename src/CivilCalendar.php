<?php

declare(strict_types=1);

namespace Arokettu\Date;

final readonly class CivilCalendar
{
    public const ITALY = 2299161; // 1582-10-15
    public const BRITAIN = 2361222; // 1752-09-14
    public const RUSSIA = 2421639; // 1918-02-14
    public const ESTONIA = 2421654; // 1918-03-01
    public const YUGOSLAVIA = 2421987; // 1919-01-28
    public const SWEDEN = 2361390; // 1753-03-01 // disregarding the madness of 1700-1712
    public const ALBANIA = 2419735; // 1912-11-28
    public const BULGARIA = 2420968; // 1916-04-14
}
