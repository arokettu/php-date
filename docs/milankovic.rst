Revised Julian (Milanković) Calendar
####################################

.. highlight:: php

.. note::
    Revised Julian calendar: https://en.wikipedia.org/wiki/Revised_Julian_calendar

Proleptic Revised Julian calendar is implemented by ``milankovic()`` helper and ``MilankovicCalendar`` factory.
Years are assumed to be in the astronomical notation. (``1 AD`` is ``1``, ``1 BC`` is ``0``, ``2 BC`` is ``-1``)
The date range is ``-5884328-11-22`` to ``5874902-11-21`` for a 32-bit system
and ``-25252754133241402-01-01`` to ``25252754133231977-10-12`` on a 64-bit system.

Factories
=========

You can create an instance of date from either date components or a ``Y-m-d`` string format (years can be negative)::

    <?php

    use Arokettu\Date\MilankovicCalendar;
    use Arokettu\Date\Month;

    $date = MilankovicCalendar::create(2900, 2, 29);
    // or use a month object
    $date = MilankovicCalendar::create(2900, Month::February, 29);
    // or parse Y-m-d
    $date = MilankovicCalendar::parse('2900-02-29');
    // years may be negative and leading zeroes are ignored
    $date = MilankovicCalendar::parse('-5000-2-000001'); // works too!

Getters
=======

There are getters for day, month, year, string representation and array representation::

    <?php

    use Arokettu\Date\JulianCalendar;

    $date = MilankovicCalendar::parse('2900-02-29');

    $date->milankovic()->getDay(); // 29
    $date->milankovic()->getMonth(); // Month::February
    $date->milankovic()->getMonthNumber(); // 2
    $date->milankovic()->getYear(); // 2900
    $date->milankovic()->getDateArray(); // [$y, $m, $d]: [2900, 2, 29]
    $date->milankovic()->toString(); // Y-m-d: 2900-02-29
