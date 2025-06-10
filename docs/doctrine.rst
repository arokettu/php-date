Doctrine Support
################

.. highlight:: php

|Packagist| |GitLab| |GitHub| |Codeberg| |Gitea|

Doctrine support is split into a separate package.

Installation
============

.. code-block:: bash

   composer require 'arokettu/date-doctrine'

Available Types
===============

* ``DateType``. UUID stored in a native DATE column.

Usage
=====

Register types::

    <?php

    use Arokettu\Uuid\Doctrine\DateType;
    use Doctrine\DBAL\Types\Type;

    // registers types directly
    Type::addType(DateType::NAME, DateType::class);

.. note:: See your framework documentation for proper configuration of custom Doctrine types.

Apply type to a model::

    <?php

    use Arokettu\Date\Date;
    use Doctrine\ORM\Mapping\{Column,Table};

    #[Entity, Table(name: 'date_object')]
    class DateObject
    {
        #[Column(type: DateType::NAME)]
        public Date $date;
    }

.. |Packagist|  image:: https://img.shields.io/packagist/v/arokettu/date-doctrine.svg?style=flat-square
   :target:     https://packagist.org/packages/arokettu/date-doctrine
.. |GitHub|     image:: https://img.shields.io/badge/get%20on-GitHub-informational.svg?style=flat-square&logo=github
   :target:     https://github.com/arokettu/date-doctrine
.. |GitLab|     image:: https://img.shields.io/badge/get%20on-GitLab-informational.svg?style=flat-square&logo=gitlab
   :target:     https://gitlab.com/sandfox/date-doctrine
.. |Codeberg|   image:: https://img.shields.io/badge/get%20on-Codeberg-informational.svg?style=flat-square&logo=codeberg
   :target:     https://codeberg.org/sandfox/date-doctrine
.. |Gitea|      image:: https://img.shields.io/badge/get%20on-Gitea-informational.svg?style=flat-square&logo=gitea
   :target:     https://sandfox.org/sandfox/date-doctrine
