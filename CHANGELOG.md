# Changelog

## 2.x

### 2.2.0

*May 3, 2024*

* Added ISO week date

### 2.1.0

*Mar 29, 2024*

* Added Revised Julian (Milanković) calendar

### 2.0.0

*Mar 29, 2024*

Forked from 1.0.0

* Moved factories to the ``Calendar`` class
  * ``Date::create()`` -> ``Calendar::create()``
  * ``Date::parse()`` -> ``Calendar::parse()``
  * ``Date::fromDateTime()`` -> ``Calendar::fromDateTime()``
  * ``Date::parseDateTimeString()`` -> ``Calendar::parseDateTimeString()``
  * Added alias ``fromString()`` for ``parse()``
* Renamed ``Date::createJulianDay()`` to ``Date::createFromJulianDay()``
* Added Julian calendar

## 1.x

### 1.0.0

*Feb 27, 2024*

* Initial release
