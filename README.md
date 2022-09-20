# This is my package array-factory

[![Latest Version on Packagist](https://img.shields.io/packagist/v/soyhuce/array-factory.svg?style=flat-square)](https://packagist.org/packages/soyhuce/array-factory)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/soyhuce/array-factory/run-tests?label=tests)](https://github.com/soyhuce/array-factory/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/soyhuce/array-factory/Fix%20PHP%20code%20style%20issues?label=code%20style)](https://github.com/soyhuce/array-factory/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![GitHub PHPStan Action Status](https://img.shields.io/github/workflow/status/soyhuce/array-factory/PHPStan?label=phpstan)](https://github.com/soyhuce/array-factory/actions?query=workflow%3APHPStan+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/soyhuce/array-factory.svg?style=flat-square)](https://packagist.org/packages/soyhuce/array-factory)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require soyhuce/array-factory
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="array-factory-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="array-factory-config"
```

This is the contents of the published config file:

```php
return [
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="array-factory-views"
```

## Usage

```php
$arrayFactory = new Soyhuce\ArrayFactory();
echo $arrayFactory->echoPhrase('Hello, Soyhuce!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Bastien Philippe](https://github.com/bastien-phi)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
