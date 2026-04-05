Easter Helper
#############

.. highlight:: php

.. versionadded:: 2.6

The Easter helper allows you to calculate both Western and Eastern dates for Easter any year.
The full range of years is supported but calculating Easter date before the year 1583 is meaningless.
This library uses `the Gauss's Easter algorithm`__.

.. __: https://en.wikipedia.org/wiki/Date_of_Easter#Gauss's_Easter_algorithm

Examples::

    <?php

    use Arokettu\Date\Easter;

    // Catholic/Protestant Easter
    echo Easter::gregorian(2026), PHP_EOL; // 2026-04-05
    // Orthodox Easter in Gregorian Calendar
    echo Easter::julian(2026), PHP_EOL; // 2026-04-12
    // Orthodox Easter in Julian Calendar
    echo Easter::julian(2026)->julian(), PHP_EOL; // 2026-03-30
