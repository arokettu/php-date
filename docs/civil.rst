Civil Calendar
##############

.. highlight:: php

Civil calendar covers a very common European case when the calendar changed from Julian to Gregorian.
Civil calendar accepts a switch date, a first date that uses Gregorian calendar, as a parameter.

Predefined Switch Dates
=======================

.. note:: https://en.wikipedia.org/wiki/List_of_adoption_dates_of_the_Gregorian_calendar_by_country

This list mostly includes non-ambiguous dates, excluding for example Germany that has more than 20 switch dates depending on exact region.

.. list-table::
    :header-rows: 1
    :widths: auto

    * * Constant
      * Switch Date
      * Comments
    * * ``CivilCalendar::MIN``
      * ``200-03-01``
      * Minimum possible. The calendar doesn't handle negative difference between Julian and Gregorian
    * * ``CivilCalendar::ITALY``
      * ``1582-10-15``
      * Invention of Gregorian. Switch date for Italy, Spain, Portugal, Poland and many other Catholic regions
    * * ``CivilCalendar::HUNGARY``
      * ``1587-11-01``
      *
    * * ``CivilCalendar::DENMARK``
      * ``1700-03-01``
      *
    * * ``CivilCalendar::BRITAIN``
      * ``1752-09-14``
      *
    * * ``CivilCalendar::SWEDEN``
      * ``1753-03-01``
      * Ignores the period of 1700-1712 when Sweden was 1 day out of sync with Julian
    * * ``CivilCalendar::ALBANIA``
      * ``1912-11-28``
      *
    * * ``CivilCalendar::BULGARIA``
      * ``1916-04-14``
      *
    * * ``CivilCalendar::RUSSIA``
      * ``1918-02-14``
      *
    * * ``CivilCalendar::ESTONIA``
      * ``1918-03-01``
      *
    * * ``CivilCalendar::YUGOSLAVIA``
      * ``1919-01-28``
      *
    * * ``CivilCalendar::GREECE``
      * ``1923-03-01``
      *

Factories
=========

You can create an instance of date from either date components or a ``Y-m-d`` string format (years can be negative)::

    <?php

    use Arokettu\Date\CivilCalendar;
    use Arokettu\Date\Month;

    // 1615-04-15 Gregorian or 1615-04-05 Julian
    $date = CivilCalendar::for(CivilCalendar::ITALY)->create(1615, Month::April, 15);
    // 1615-04-25 Gregorian or 1615-04-15 Julian
    $date = CivilCalendar::for(CivilCalendar::BRITAIN)->create(1615, Month::April, 15);

    // DomainException: Switch day cannot be earlier than "200-03-01", "200-01-01" (Julian day 1794109) given
    $date = CivilCalendar::for(Calendar::parse('200-01-01'))->create(1615, Month::April, 15);

    // DomainException: "1582-10-10" likely belongs to the switch gap. Dates between "1582-10-04" and "1582-10-15" are invalid
    $date = CivilCalendar::for(CivilCalendar::ITALY)->create(1582, Month::October, 10);

Getters and Helpers
===================

There are getters for day, month, year, string representation and array representation,
also calendar has helpers to reuse the switch date::

    <?php

    use Arokettu\Date\CivilCalendar;
    use Arokettu\Date\Date;

    $italy = CivilCalendar::for(CivilCalendar::ITALY);

    // use switch date as an example
    $date = Date::createFromJulianDay(CivilCalendar::ITALY); // Gregorian

    $date->civil(CivilCalendar::ITALY)->getDay(); // 15
    // or
    $italy->civilDate($date)->getDay(); // 15

    $date->civil(CivilCalendar::ITALY)->getMonth(); // Month::October
    // or
    $italy->civilDate($date)->getMonth(); // Month::October

    $date->civil(CivilCalendar::ITALY)->getMonthNumber(); // 10
    // or
    $italy->civilDate($date)->getMonthNumber(); // 10

    $date->civil(CivilCalendar::ITALY)->getYear(); // 1582
    // or
    $italy->civilDate($date)->getYear(); // 1582

    $date->civil(CivilCalendar::ITALY)->getDateArray(); // [$y, $m, $d]: [1582, 10, 15]
    // or
    $italy->civilDate($date)->getDateArray(); // [$y, $m, $d]: [1582, 10, 15]

    $date->civil(CivilCalendar::ITALY)->toString(); // Y-m-d: 1582-10-15
    // or
    $italy->dateToString($date); // Y-m-d: 1582-10-15

    // going over the switch date will get us Julian
    echo $date->subDays(1)->civil(CivilCalendar::ITALY)->toString(); // 1582-10-04
