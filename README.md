# Convert HTML to PDF with Google Chrome

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jbreuer95/laravel-headless-pdf.svg?style=flat-square)](https://packagist.org/packages/jbreuer95/laravel-headless-pdf)
[![Total Downloads](https://img.shields.io/packagist/dt/jbreuer95/laravel-headless-pdf.svg?style=flat-square)](https://packagist.org/packages/jbreuer95/laravel-headless-pdf)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/jbreuer95/laravel-headless-pdf/run-tests.yml?branch=master&label=tests&style=flat-square)](https://github.com/jbreuer95/laravel-headless-pdf/actions/workflows/run-tests.yml)
[![GitHub PHPStan Action Status](https://img.shields.io/github/actions/workflow/status/jbreuer95/laravel-headless-pdf/phpstan.yml?branch=master&label=phpstan&style=flat-square)](https://github.com/jbreuer95/laravel-headless-pdf/actions/workflows/phpstan.yml)
[![GitHub Pint Action Status](https://img.shields.io/github/actions/workflow/status/jbreuer95/laravel-headless-pdf/fix-php-code-style-issues.yml?branch=master&label=laravel%20pint&style=flat-square)](https://github.com/jbreuer95/laravel-headless-pdf/actions/workflows/fix-php-code-style-issues.yml)

This package allows you to easily convert HTML to PDF using Google Chrome through Selenium, without needing Node.js.
It is inspired by Spatie's [laravel-pdf](https://github.com/spatie/laravel-pdf) package,
which uses BrowserShot and Puppeteer, but our solution offers a more PHP-centric approach using Selenium.

## Installation

You can install the package via Composer:

```bash
composer require jbreuer95/laravel-headless-pdf
```

After installing, you must download headless Google Chrome using the following Artisan command:

```bash
php artisan headless-pdf:install
```

To customize the package configuration, you can publish the configuration file:

```bash
php artisan vendor:publish --tag="laravel-headless-pdf-config"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

Converting HTML to PDF is simple with this package. Here’s a basic example:

```php
use PDF;

$pdf = PDF::html('<html><body><h1>Hello World</h1></body></html>');

// Save or stream the generated PDF
$pdf->save('file.pdf');  // Save the PDF to a file
$pdf->stream();          // Stream the PDF directly to the browser
```

## License

This package is open-sourced software licensed under the MIT License.  
Please see [License File](LICENSE.md) for more information.
