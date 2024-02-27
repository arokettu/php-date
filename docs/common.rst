Common
######

.. highlight:: php

Date Object
===========

The ``Date`` object is immutable.
Internally it only contains the set Julian day integer.

Arithmetic
----------

You can add or subtract days::

    <?php

    use Arokettu\Date\Date;

    $date = Date::parse('2012-12-21');

    echo $date->add(100); // 2013-03-31, 100 days after the apocalypse
    echo $date->subDays(100); // 2012-09-12 // = echo $date->add(-100);

    $today = Date::today();

    // days since the apocalypse :D
    echo $today->sub($date); // 4085 as of Feb 27, 2024

Calendar Agnostic Methods
=========================

Today
-----

Gets a current date for the default or specific time zone::

    <?php

    use Arokettu\Date\Date;

    echo Date::today(); // current system date
    echo Date::today(new DateTimeZone('Asia/Tokyo')); // current date in Tokyo

WeekDay
-------

Week day can be retrieved as an instance of the WeekDay enum or as a number (1 = Monday, 7 = Sunday)::

    <?php

    use Arokettu\Date\Date;

    $date = Date::today();
    $date->getWeekDay(); // like WeekDay::Tuesday
    $date->getWeekDayNumber(); // 2 == WeekDay::Tuesday->value

DateTime Interoperability
=========================

Import/Export date from DateTime objects.

Import
------

Create an instance of Date from an instance of DateTimeInterface or by using DateTime's own parser::

    <?php

    use Arokettu\Date\Date;

    $dt = new DateTime('Feb 28, 2024');
    $date = Date::fromDateTime($dt);
    // or
    $date = Date::parseDateTimeString('Feb 28, 2024');
    // or any other expression DateTime supports:
    $date = Date::parseDateTimeString('tomorrow');

Export
------

Get a DateTimeImmutable object corresponding to midnight at a given date in a default or a specified time zone::

    <?php

    use Arokettu\Date\Date;

    $date->toDateTime(); // timestamp at given date midnight system time
    $date->toDateTime(new DateTimeZone('Asia/Tokyo')); // timestamp at given date midnight Tokyo
