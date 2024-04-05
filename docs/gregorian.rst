Gregorian Calendar
##################

.. highlight:: php

.. note::
    Gregorian calendar: https://en.wikipedia.org/wiki/Gregorian_calendar

Proleptic Gregorian calendar is the default calendar of the library.
Years are assumed to be in the astronomical notation. (``1 AD`` is ``1``, ``1 BC`` is ``0``, ``2 BC`` is ``-1``)
The date range is ``-5884323-05-15`` to ``5874898-06-03`` for a 32-bit system
and ``-25252734927771267-04-30`` to ``25252734927761842-06-20`` on a 64-bit system.

Factories
=========

You can create an instance of date from either date components or a ``Y-m-d`` string format (years can be negative)::

    <?php

    use Arokettu\Date\Calendar;
    use Arokettu\Date\Month;

    $date = Calendar::create(2012, 12, 21);
    // or use a month object
    $date = Calendar::create(2012, Month::December, 21);
    // or parse Y-m-d
    $date = Calendar::parse('2012-02-21');
    // years may be negative and leading zeroes are ignored
    $date = Calendar::parse('-5000-2-000001'); // works too!

Getters
=======

There are getters for day, month, year, string representation and array representation::

    <?php

    use Arokettu\Date\Calendar;

    $date = Calendar::parse('2012-12-21');

    $date->getDay(); // 21
    $date->getMonth(); // Month::December
    $date->getMonthNumber(); // 12
    $date->getYear(); // 2012
    $date->getDateArray(); // [$y, $m, $d]: [2012, 12, 24]
    $date->toString(); // Y-m-d: 2012-12-24
