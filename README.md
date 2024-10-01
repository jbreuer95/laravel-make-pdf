# Convert HTML to PDF with Google Chrome

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jbreuer95/laravel-headless-pdf.svg?style=flat-square)](https://packagist.org/packages/jbreuer95/laravel-headless-pdf)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/jbreuer95/laravel-headless-pdf/run-tests.yml?branch=master&label=tests&style=flat-square)](https://github.com/jbreuer95/laravel-headless-pdf/actions?query=workflow%3Arun-tests+branch%3Amaster)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/jbreuer95/laravel-headless-pdf/fix-php-code-style-issues.yml?branch=master&label=code%20style&style=flat-square)](https://github.com/jbreuer95/laravel-headless-pdf/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/jbreuer95/laravel-headless-pdf.svg?style=flat-square)](https://packagist.org/packages/jbreuer95/laravel-headless-pdf)

Inspired by Spatie's package [laravel-pdf](https://github.com/spatie/laravel-pdf), only that package relies on BrowserShot that relies on Puppeteer that relies on Node.js

This packages uses Selenium to communicate with Google Chrome using only PHP.  
Laravel Dusk also uses Selenium to run its browser tests.

## Installation

You can install the package via composer:

```bash
composer require jbreuer95/laravel-headless-pdf
```

You can download (or update) chrome with:

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
$pdf = PDF::html('<html><body><h1>Hello World</h1></body></html>')
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
