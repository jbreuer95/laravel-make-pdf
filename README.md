# Convert HTML to PDF with headless Chrome

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jbreuer95/laravel-make-pdf.svg?style=flat-square)](https://packagist.org/packages/jbreuer95/laravel-make-pdf)
[![Total Downloads](https://img.shields.io/packagist/dt/jbreuer95/laravel-make-pdf.svg?style=flat-square)](https://packagist.org/packages/jbreuer95/laravel-make-pdf)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/jbreuer95/laravel-make-pdf/run-tests.yml?branch=master&label=tests&style=flat-square)](https://github.com/jbreuer95/laravel-make-pdf/actions/workflows/run-tests.yml)
[![GitHub PHPStan Action Status](https://img.shields.io/github/actions/workflow/status/jbreuer95/laravel-make-pdf/phpstan.yml?branch=master&label=phpstan&style=flat-square)](https://github.com/jbreuer95/laravel-make-pdf/actions/workflows/phpstan.yml)
[![GitHub Pint Action Status](https://img.shields.io/github/actions/workflow/status/jbreuer95/laravel-make-pdf/fix-php-code-style-issues.yml?branch=master&label=laravel%20pint&style=flat-square)](https://github.com/jbreuer95/laravel-make-pdf/actions/workflows/fix-php-code-style-issues.yml)

This package allows you to easily convert HTML to PDF using headless Chrome through Selenium, without needing Node.js.
It is inspired by Spatie's [laravel-pdf](https://github.com/spatie/laravel-pdf) package,
which uses BrowserShot and Puppeteer, but our solution offers a more PHP-centric approach using Selenium.

## Requirements

Laravel Make PDF requires **PHP 8.1+** and **Laravel 10+**.

## Installation & Setup

You can install the package via Composer:

```bash
composer require jbreuer95/laravel-make-pdf
```

After installation, download headless Chrome using the following Artisan command:

```bash
php artisan make-pdf:install
```

To customize the package configuration, publish the configuration file:

```bash
php artisan vendor:publish --tag="laravel-make-pdf-config"
```

Here is the content of the published config file:

```php
return [
    // Configuration options will go here
];
```

## Usage

Converting HTML to PDF with this package is simple and efficient. Below are a few common use cases:

### Basic Example

Convert a Blade view to a PDF and stream it to the browser:

```php
use Breuer\MakePDF\Facades\PDF;

Route::get('/', function () {
    return PDF::view('view.name', [])->response();
});
```

Or force the browser to download the PDF file

```php
return PDF::view('view.name', [])->download();
```

### Options

#### Render Raw HTML:

Instead of passing a Blade view, you can directly pass HTML:

```php
PDF::html('<h1>Hello World</h1>')
```

#### Header and Footer

You can include a view in the header and footer of every page:

```php
->headerView('view.header')
->footerView('view.footer')
```

Alternatively, set raw HTML for the header and footer:

```php
->headerHtml('<div>My header</div>')
->footerHtml('<div>My footer</div>')
```

In the header or footer, the following placeholders can be used and will be replaced with their print-specific values:

```html
<span class="date"></span>
<span class="title"></span>
<span class="pageNumber"></span>
<span class="totalPages"></span>
```

**Note:** The header and footer do not inherit the same CSS as the main content, and the default font size is 0. You should include any required CSS directly in the header/footer. Here’s an example of a styled footer view:

```html
<style>
    footer {
        font-size: 13px;
        color: black;
    }
</style>
<footer>
    <span class="date"></span>
    <span class="pageNumber"></span> / <span class="totalPages"></span>
</footer>
```

#### Custom Filename

Define a custom name for the PDF when downloading from the browser.
The `.pdf` extension is automatically appended if omitted:

```php
->name('custom_filename')
```

#### Save to File

Use the `save` method to store the PDF at a given file path:

```php
->save('/path/to/save/yourfile.pdf')
```

#### Stream PDF

Display the PDF directly in the browser without saving it to disk:

```php
->response()
```

#### Force Download

Prompt the browser to immediately download the PDF:

```php
->download()
```

## License

This package is open-sourced software licensed under the MIT License.  
Please see the [License File](LICENSE.md) for more information.
