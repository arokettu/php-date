Julian Calendar
###############

.. highlight:: php

.. note::
    Julian calendar: https://en.wikipedia.org/wiki/Julian_calendar

Proleptic Julian calendar is implemented by ``julian()`` helper and ``JulianCalendar`` factory.
Years are assumed to be in the astronomical notation. (``1 AD`` is ``1``, ``1 BC`` is ``0``, ``2 BC`` is ``-1``)
The date range is ``-5884202-03-16`` to ``5874777-10-17`` for a 32-bit system
and ``-25252216391119773-08-11`` to ``25252216391110348-05-22`` on a 64-bit system.

Factories
=========

You can create an instance of date from either date components or a ``Y-m-d`` string format (years can be negative)::

    <?php

    use Arokettu\Date\JulianCalendar;
    use Arokettu\Date\Month;

    $date = JulianCalendar::create(2100, 2, 29);
    // or use a month object
    $date = JulianCalendar::create(2100, Month::February, 29);
    // or parse Y-m-d
    $date = JulianCalendar::parse('2100-02-29');
    // years may be negative and leading zeroes are ignored
    $date = JulianCalendar::parse('-5000-2-000001'); // works too!

Getters
=======

There are getters for day, month, year, string representation and array representation::

    <?php

    use Arokettu\Date\JulianCalendar;

    $date = JulianCalendar::parse('2100-02-29');

    $date->julian()->getDay(); // 29
    $date->julian()->getMonth(); // Month::February
    $date->julian()->getMonthNumber(); // 2
    $date->julian()->getYear(); // 2100
    $date->julian()->getDateArray(); // [$y, $m, $d]: [2100, 2, 29]
    $date->julian()->toString(); // Y-m-d: 2100-02-29
