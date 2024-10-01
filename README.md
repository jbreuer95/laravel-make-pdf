# This is my package laravel-headless-pdf

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jbreuer95/laravel-headless-pdf.svg?style=flat-square)](https://packagist.org/packages/jbreuer95/laravel-headless-pdf)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/jbreuer95/laravel-headless-pdf/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/jbreuer95/laravel-headless-pdf/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/jbreuer95/laravel-headless-pdf/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/jbreuer95/laravel-headless-pdf/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/jbreuer95/laravel-headless-pdf.svg?style=flat-square)](https://packagist.org/packages/jbreuer95/laravel-headless-pdf)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require jbreuer95/laravel-headless-pdf
```

You can download or update the chrome dependency with:

```bash
php artisan headless-pdf:install
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-headless-pdf-config"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

```php
$pdf = new Breuer\PDF();
echo $pdf->echoPhrase('Hello, Breuer!');
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
