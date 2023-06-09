# Testing! Unit, Integration & Functional all in Symfony 🧐

This repository contains the screencast code, script and true grit
behind the "PHPUnit: Testing with a Bite!" tutorial series on Symfonycasts!

## Setup

If you've just downloaded the code, congratulations!!

To get it working, follow these steps:

**Download Composer dependencies**

Make sure you have [Composer installed](https://getcomposer.org/download/)
and then run:

```
composer install
```

You may alternatively need to run `php composer.phar install`, depending
on how you installed Composer.

**Start the Symfony web server**

You can use Nginx or Apache, but Symfony's local web server
works even better.

To install the Symfony local web server, follow
"Downloading the Symfony client" instructions found
here: https://symfony.com/download - you only need to do this
once on your system.

Then, to start the web server, open a terminal, move into the
project, and run:

```
symfony serve
```

(If this is your first time using this command, you may see an
error that you need to run `symfony server:ca:install` first).

Now check out the site at `https://localhost:8000`

Have fun!

## Have Ideas, Feedback or an Issue?

If you have suggestions or questions, please feel free to
open an issue on this repository or comment on the course
itself. We're watching both :).

## Thanks!

And as always, thanks so much for your support and letting
us do what we love!

<3 Your friends at SymfonyCasts

----

# Notes

## Install Symfony Tests bundles

```
composer require --dev symfony/test-pack
```

## Execute PHPUnit

```
vendor/bin/phpunit
# or
bin/phpunit

```

And with human-readable output

```
vendor/bin/phpunit --testdox
```

## PHPUnit documentation

[> Documentation](https://docs.phpunit.de/en/main/)

## Incomplete tests

If test is not implemented, we can use

```
$this->markTestIncomplete();
```

# Execute only certain test

```
vendor/bin/phpunit --filter testThingToTest
```
