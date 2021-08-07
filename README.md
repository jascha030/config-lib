# Composer project template

Description: Minimal library for handling PHP Array config files.

## Getting started

## Prerequisites

* Php `^7.4 || ^8.0`
* Composer `^2`

### Installation

```shell
composer require jascha030/composer-template
```

#### Distribution

Alternative steps for dist build.

```shell
composer require --no-dev jascha030/composer-template
```

## Usage

The library is built around the `ConfigStoreInterface` and it's main implementation `ConfigStore`.
Here are some simple examples which cover all of the functionalities:

```php
<?php

use Jascha030\ConfigurationLib\Config\ConfigStore;

//  Load composers autoloader. 
require_once __DIR__ . '/vendor/autoload.php';

// Directory in which your config files reside.
$dir = __DIR__ . '/path/to/config/files';
$configStore = new ConfigStore($dir);

// Make sure to call the load method before retrieving values.
$configStore->load();

// Retrieve value firstName from any of the config files in your directory.
if ($configStore->keyExists('firstName')) {
    $firstName = $configStore->get('firstName');
}

// Alternatively specify the exact file, using dot notation to retrieve firstName from /path/to/config/files/user.php
$configStore->get('user.firstName');

// Retrieve all values from /path/to/config/files/user.php
if ($configStore->configFileExists('user')) {
    $userConfig = $configStore->getConfig('user');
    $firstName  = $userConfig['firstName'];
}

// You can get the config file from an option's key.
$configFile = $configStore->getFileByOptionKey('firstName');

// You can validate if an option exists in a given file
$configStore->hasKey('user', 'firstName');
```

### Testing

Included with the package are a set of Unit tests using `phpunit/phpunit`. For ease of use a composer script command is
defined to run the tests.

```shell
composer run phpunit
```

A code coverage report is generated in the project's root as `cov.xml`. The `cov.xml` file is not ignored in the
`.gitignore` by default.

### Code style & Formatting

A code style configuration for `friendsofphp/php-cs-fixer` is included, defined in `.php-cs-fixer.dist.php`.

To use php-cs-fixer without having it necessarily installed globally, a composer script command is also included to
format php code using the provided config file and the vendor binary of php-cs-fixer.

```shell
composer run format
```

> **Note:** [https://mlocati.github.io/php-cs-fixer-configurator/#version:3.0](https://mlocati.github.io/php-cs-fixer-configurator/#version:3.0)
is a nifty tool to compose and export a custom code style configuration, I encourage anyone interested to use this tool.

## License

This composer package is open-sourced software licensed under the [MIT License](https://github.com/jascha030/composer-template/blob/master/LICENSE.md)
