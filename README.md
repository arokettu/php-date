# Date

[![PHP]][Packagist Link]
[![License]][License Link]
[![GitLab CI]][GitLab CI Link]
[![Codecov]][Codecov Link]

[PHP]: https://img.shields.io/packagist/php-v/arokettu/date.svg?style=flat-square
[License]: https://img.shields.io/packagist/l/arokettu/date.svg?style=flat-square
[GitLab CI]: https://img.shields.io/gitlab/pipeline/sandfox/php-date/master.svg?style=flat-square
[Codecov]: https://img.shields.io/codecov/c/gl/sandfox/php-date?style=flat-square

[Packagist Link]: https://packagist.org/packages/arokettu/date
[GitLab CI Link]: https://gitlab.com/sandfox/php-date/-/pipelines
[Codecov Link]: https://codecov.io/gl/sandfox/php-date/
[License Link]: LICENSE.md

A class for php to work with pure dates (without time).

* Works with the proleptic Gregorian calendar by default
* Uses Julian day internally
* Does not depend on the calendar extension
* Correctly supports full integer range for the dates
  (-5884323-05-15 to 5874898-06-03 on a 32-bit system, even more on 64-bit)
* Additional calendars:
  * [Julian](https://en.wikipedia.org/wiki/Julian_calendar)
  * [Revised Julian (Milanković)](https://en.wikipedia.org/wiki/Revised_Julian_calendar)

## Usage

The library:

```php
<?php

use Arokettu\Date\Calendar;
use Arokettu\Date\Date;
use Arokettu\Date\JulianCalendar;
use Arokettu\Date\Month;

// creation
$date = Date::today(); // example: 2024-02-27
// or
$date = Calendar::create(2024, Month::February, 27);
// or
$date = Calendar::fromDateTime(new DateTime('Feb 27, 2024')); // truncates time
// or
$date = Calendar::parse('2024-02-27'); // Y-m-d is expected, years can be negative
// or
$date = Calendar::parseDateTimeString('Feb 27, 2024');

// alternative calendars
$date = JulianCalendar::parse('2024-02-14');

// getters
$date->getDay(); // 27
$date->getMonth(); // Month::February
$date->getMonthNumber(); // 2
$date->getYear(); // 2024
$date->getWeekDay(); // WeekDay::Tuesday
$date->getJulianDay(); // 2460368
$date->toDateTime(); // DateTimeImmutable('2024-02-27') // midnight in a default timezone
$date->toString(); // "2024-02-27"

// alternative calendar getters
$date->julian()->getDay(); // 14
$date->julian()->toString(); // "2024-02-14"
```

## Installation

```bash
composer require arokettu/date
```

## Documentation

Read full documentation here: <https://sandfox.dev/php/date.html>

Also on Read the Docs: <https://php-date.readthedocs.io/>

## Support

Please file issues on our main repo at GitLab: <https://gitlab.com/sandfox/php-uuid/-/issues>

Feel free to ask any questions in our room on Gitter: <https://gitter.im/arokettu/community>

## License

The library is available as open source under the terms of the [MIT License][License Link].
