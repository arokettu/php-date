<?php

declare(strict_types=1);

namespace Arokettu\Date\Calendars;

use Arokettu\Date\Date;

final readonly class CivilDate
{
    use GregorianLikeDate;

    public function __construct(
        public Date $date,
        public int $switchDate,
    ) {
    }

    public function getDateArray(): array
    {
        if ($this->date->julianDay < $this->switchDate) {
            return $this->date->julian()->getDateArray();
        }

        return $this->date->getDateArray();
    }
}
