Petstock Assessment
===================

A simple Symfony app with a simple REST API.

Installation
------------

Install dependencies from Composer:

```$ composer install```

Provision database using Doctrine:

```$ php bin/console doctrine:schema:update```

Run the application:

1. Run ```$ php bin/console server:run``` to start a local webserver.
2. Configure your webserver to point to the directory this application was installed in according to the Symfony documentation.

Run unit tests:

PHPUnit is already configured.

To run the tests, simply run PHPUnit:

```$ ./vendor/bin/phpunit```
