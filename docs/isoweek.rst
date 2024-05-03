ISO Week Date
#############

.. highlight:: php

.. note::
    ISO week date: https://en.wikipedia.org/wiki/ISO_week_date

ISO week date is implemented by ``isoWeek()`` helper and ``IsoWeekCalendar`` factory.
Years are assumed to be in the astronomical notation. (``1 AD`` is ``1``, ``1 BC`` is ``0``, ``2 BC`` is ``-1``)
The date range is ``-5884323-W19-6`` to ``5874898-W23-2`` for a 32-bit system
and ``-25252734927771267-W17-7`` to ``25252734927761842-W25-1`` on a 64-bit system.

.. note::
    ISO week date is also supported by the native DateTime type and the IntlDateFormatter component

Factories
=========

You can create an instance of date from either date components or a ``y-Ww-d`` string format (years can be negative)::

    <?php

    use Arokettu\Date\IsoWeekCalendar;
    use Arokettu\Date\WeekDay;

    $date = IsoWeekCalendar::create(2012, 12, 2); // 2012-03-20
    // or use a WeekDay object
    $date = IsoWeekCalendar::create(2012, 12, WeekDay::Tuesday);
    // or parse y-Ww-d
    $date = IsoWeekCalendar::parse('2012-W12-2');
    // W is optional
    $date = IsoWeekCalendar::parse('2012-12-2');
    // years may be negative and leading zeroes are ignored
    $date = IsoWeekCalendar::parse('-5000-W2-000001'); // works too!
    // short form
    // (for the short form W is required and leading zeros are not allowed)
    $date = IsoWeekCalendar::parse('2012W122');

Getters
=======

There are getters for day, week, year, string representation and array representation::

    <?php

    use Arokettu\Date\IsoWeekCalendar;

    $date = $date = IsoWeekCalendar::create(2012, 12, 2);

    $date->isoWeek()->getWeekDay(); // WeekDay::Tuesday
    $date->isoWeek()->getWeekDayNumber(); // 2
    $date->isoWeek()->getWeek(); // 12
    $date->isoWeek()->getYear(); // 2012
    $date->isoWeek()->getDateArray(); // [$y, $w, $d]: [2012, 12, 2]
    $date->isoWeek()->toString(); // y-Ww-d: 2012-W12-2
