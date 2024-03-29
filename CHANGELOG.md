# Changelog

## 2.x

### next

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
