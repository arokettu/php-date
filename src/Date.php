<?php

declare(strict_types=1);

namespace Arokettu\Date;

final readonly class Date
{
    public function __construct(
        public int $julianDay,
    ) {}
}
