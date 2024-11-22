<?php

declare(strict_types=1);

namespace Arokettu\Date\Calendars;

use Arokettu\Date\CivilCalendar;
use Arokettu\Date\Date;
use DomainException;

final readonly class CivilDate
{
    use GregorianLikeDate;

    public function __construct(
        public Date $date,
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
        if ($this->date->julianDay < $this->switchDay) {
            return $this->date->julian()->getDateArray();
        }

        return $this->date->getDateArray();
    }
}
