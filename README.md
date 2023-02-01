# Iterable - Rewindable Generators

Wrapper to make generators rewindable with limited Helper class for iterator related functions.

## Getting started

## Prerequisites

* php: `^8.1`
* Composer `^2.3` (prefered)

### Installation

```shell
composer require jascha030/iterable
```

## Usage

**The issue:**

```php
<?php declare(strict_types=1);

$generator = function (): Generator {
    yield 1;
    yield 2;
    yield 3;
}

foreach($generator() as $int) {
    echo $int; // output: "123".
}

/**
 * Traversing your generator again would lead to a error, being thrown.
 */
foreach($generator() as $int) { // Throws \Exception.
    echo $int;
}
```

**The solution:**

```php
<?php declare(strict_types=1);

use Jascha030\Iterable\Rewindable\RewindableGenerator;

include_once __DIR__ . 'vendor/bootstrap.php';

$generator = new RewindableGenerator::from(function (): Generator {
    yield 1;
    yield 2;
    yield 3;
});

foreach($generator() as $int) {
    echo $int; // output: 123
}

foreach($generator() as $int) {
    echo $int; // output: 123
}
```

## Development

### Note on development dependencies

To minimize the chance of namespace or dependency collision some dev-dependencies are provided as phar archives using [phive](https://phar.io/), which is an alternative package manager that focusses on providing dependencies as phar archives.

Install Phive by running these steps in your terminal.

```sh
wget -O phive.phar https://phar.io/releases/phive.phar
wget -O phive.phar.asc https://phar.io/releases/phive.phar.asc
gpg --keyserver hkps://keys.openpgp.org --recv-keys 0x9D8A98B29B2D5D79
gpg --verify phive.phar.asc phive.phar
chmod +x phive.phar
sudo mv phive.phar /usr/local/bin/phive
```

For alternative installation methods check [phar.io](https://phar.io/).

### Testing

Included with the package are a set of Unit tests using `phpunit/phpunit`. For ease of use a composer script command is
defined to run the tests.

The default configuration will be used when using the `test` command, which is defined at `phpunit.dist.xml`.

```shell
composer test

# For older versions
composer run test
```

A code coverage report is generated in the project's root as `cov.xml`. The `cov.xml` file is not ignored in the
`.gitignore` by default. You are encouraged to commit the latest code coverage report, when deploying new features.

### Code style & Formatting

A code style configuration for `friendsofphp/php-cs-fixer` is included, defined in `.php-cs-fixer.dist.php`. By default,
it includes the `PSR-1` and `PSR-12` presets. You can customize or add rules in `.php-cs-fixer.dist.php`.

To use php-cs-fixer without having it necessarily installed globally, a composer script command is also included to
format php code using the provided config file and the vendor binary of php-cs-fixer.

```shell
composer format

# For older versions
composer run format
```

## Development and contribution

Instructions regarding further contribution to the package itself.

## Inspiration and appreciation

This is a personal take on the rewindable generator from nikic/iter using more modern syntax (php 8.1).
So full credits for the the idea and the initial implementation to [nikic](https://github.com/nikic).

## License

This composer package is an open-sourced software licensed under
the [MIT License](https://github.com/jascha030/iterable/blob/master/LICENSE.md)
