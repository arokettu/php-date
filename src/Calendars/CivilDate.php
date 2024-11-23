<?php

declare(strict_types=1);

namespace Arokettu\Date\Calendars;

use Arokettu\Date\CivilCalendar;
use Arokettu\Date\Date;
use DomainException;

final readonly class CivilDate
{
    use GregorianLikeDate;

    private Date|JulianCalendarDate $innerDate;

    public function __construct(
        public int $julianDay,
        public int $switchDay,
    ) {
        if ($switchDay < CivilCalendar::MIN) {
            throw new DomainException(sprintf(
                'Switch day cannot be earlier than "200-03-01", "%s" (Julian day %d) given',
                new Date($switchDay),
                $switchDay,
            ));
        }
    }

    public function getDateArray(): array
    {
        $this->innerDate ??= $this->julianDay < $this->switchDay ?
            new JulianCalendarDate($this->julianDay) :
            new Date($this->julianDay);

        return $this->innerDate->getDateArray();
    }
}
