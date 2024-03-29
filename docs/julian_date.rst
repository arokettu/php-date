Julian Date
###########

.. highlight:: php

.. note::
    Julian day / Julian date: https://en.wikipedia.org/wiki/Julian_day

.. note::
    Chronological Julian date: https://www.hermetic.ch/cal_stud/chron_jdate.htm

Julian Day
==========

Julian day is the internal implementation for the object.
Julian day value accepts any integer from PHP_INT_MIN to PHP_INT_MAX.

::

    <?php

    use Arokettu\Date\Date;

    // use a semantic factory
    $date = Date::createFromJulianDay(2460368);
    // or construct directly
    $date = new Date(2460368);

    echo $date->getJulianDay(); // 2460368

Julian Date
===========

By default the library assumes that the Julian day value in the object is an integer part of the chronological Julian date.
(conversion to/from DateTime assumes that the date means a timestamp for the midnight in the given time zone)
If you need to work with the default astronomical Julian date, you can explicitly use '-12:00' time zone::

    <?php

    use Arokettu\Date\Date;

    $tzjd = new DateTimeZone('-12:00');
    $date = Date::today($tzjd); // current integer value of the Julian date

    $dt = $date->toDateTime($tzjd) // Noon UTC at given Julian date
        ->setTimezone(new DateTimeZone('UTC')); // optionally set UTC explicitly
