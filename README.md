# This is my package resource-generator-widget

[![Latest Version on Packagist](https://img.shields.io/packagist/v/lartisan/resource-generator-widget.svg?style=flat-square)](https://packagist.org/packages/lartisan/resource-generator-widget)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/lartisan/resource-generator-widget/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/lartisan/resource-generator-widget/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/lartisan/resource-generator-widget/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/lartisan/resource-generator-widget/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/lartisan/resource-generator-widget.svg?style=flat-square)](https://packagist.org/packages/lartisan/resource-generator-widget)



This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require lartisan/resource-generator-widget
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="resource-generator-widget-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="resource-generator-widget-config"
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="resource-generator-widget-views"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

```php
$resourceGenerator = new Lartisan\ResourceGenerator();
echo $resourceGenerator->echoPhrase('Hello, Lartisan!');
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

- [Cristian Iosif](https://github.com/lartisan)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
