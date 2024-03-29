Upgrade
#######

.. highlight:: php

1.x to 2.0
==========

Factory methods were moved or renamed:

* ``Date::today()`` is unchanged
* ``Date::createJulianDay()`` -> ``Date::createFromJulianDay()``
* ``Date::create()`` -> ``Calendar::create()``
* ``Date::parse()`` -> ``Calendar::parse()``
* ``Date::fromDateTime()`` -> ``Calendar::fromDateTime()``
* ``Date::parseDateTimeString()`` -> ``Calendar::parseDateTimeString()``
